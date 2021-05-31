import requests
from bs4 import BeautifulSoup
import pandas as pd
import concurrent.futures
from concurrent.futures import ThreadPoolExecutor
import threading
from ManagerFarmaci import inserimentoDB
import schedule
import time

from requests.api import head
lock = threading.Lock()
listaInformazioniFarmaci = []


def scrapingProdotto(farmaco):
    nomeProdotto = farmaco.find("h2", class_="product-name").text
    link = farmaco.find(
        "h2", class_="product-name").findChildren("a")[0].get("href")
    img = farmaco.find(
        "a", class_="product-image").findChildren("img")[0].get("src")
    descrizione = farmaco.find("div", class_="desc std").text

    prezzoVecchio = farmaco.find("span", class_="price")
    if prezzoVecchio == None:
        prezzoVecchio = -1
    else:
        prezzoVecchio = prezzoVecchio.text
    prezzoNuovo = farmaco.find(
        "p", class_="special-price").findChildren("span", class_="price")[0].text

    k = requests.get(link, headers=headers).text
    dettagliFarmaco = BeautifulSoup(k, 'html.parser')
    titolo = dettagliFarmaco.find(
        "dl", class_="farma-details").findChildren("dt")[0].text
    if titolo == 'Sku: ':
        minsan = dettagliFarmaco.find(
            "dl", class_="farma-details").findChildren("dd")[0].text
    else:
        minsan = dettagliFarmaco.find(
            "dl", class_="farma-details").findChildren("dd")[1].text

    # parsificazione
    prezzoVecchioAggiornato = ''
    for lettera in prezzoVecchio:
        if lettera.isdigit() == True:
            prezzoVecchioAggiornato = prezzoVecchioAggiornato + lettera

    prezzoNuovoAggiornato = ''
    for lettera in prezzoNuovo:
        if lettera.isdigit() == True:
            prezzoNuovoAggiornato = prezzoNuovoAggiornato + lettera

    descrizione = descrizione.replace("\xa0", '')
    descrizione = descrizione.replace('\n', '')

    # inserimento delle informazioni in dizionario e poi lista
    informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzoNuovo': prezzoNuovoAggiornato,
                           'prezzoVecchio': prezzoVecchioAggiornato, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
    #informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
    # listaInformazioniFarmaci.append(informazioniFarmaco)

    print(nomeProdotto)
    # sezione critica
    lock.acquire()
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()


def invioRichieste(paginaIniziale, paginaFinale):
    pagina = paginaIniziale

    while pagina <= paginaFinale:
        # scorrimento delle pagine
        k = requests.get(
            'https://www.farmaciadifiducia.com/it/farmaci-da-banco-on-line/?p=' + str(pagina), headers=headers).text
        soup = BeautifulSoup(k, 'html.parser')
        listaFarmaci = soup.find_all("li", class_="item")

        with ThreadPoolExecutor(max_workers=10) as executor:
            results = executor.map(scrapingProdotto, listaFarmaci)

        pagina = pagina + 1


def thread1():
    invioRichieste(1, 10)


def thread2():
    invioRichieste(11, 20)


def thread3():
    invioRichieste(21, 30)


def thread4():
    invioRichieste(31, 40)


def thread5():
    invioRichieste(41, 50)


def thread6():
    invioRichieste(51, 58)


def startScraping():
    with concurrent.futures.ThreadPoolExecutor(max_workers=6) as executor:
        executor.submit(thread1)
        executor.submit(thread2)
        executor.submit(thread3)
        executor.submit(thread4)
        executor.submit(thread5)
        executor.submit(thread6)


def operazioneGiornaliera():
    startScraping()

    for informazioniFarmaco in listaInformazioniFarmaci:
        inserimentoDB(informazioniFarmaco)


def main():
    operazioneGiornaliera()


if __name__ == '__main__':
    main()


# schedule.every().day.at("03:00").do(operazioneGiornaliera)

# while True:
#    schedule.run_pending()
#    time.sleep(60)
