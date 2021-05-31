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
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

listaInformazioniFarmaci = []


def scrapingProdotto(farmaco):
    link = farmaco.find("div", class_="product-meta").findChildren("div", class_="group-item")[
        0].findChildren("h3", class_="name")[0].findChildren("a")[0].get("href")
    nomeProdotto = farmaco.find("h3", class_="name").findChildren("a")[0].text

    if farmaco.find("span", class_="price-old") != None:
        prezzoNuovo = farmaco.find("span", class_="price-new").text
        prezzoVecchio = farmaco.find("span", class_="price-old").text
    else:
        prezzoNuovo = farmaco.find("div", class_="price").text
        prezzoVecchio = -1

    img = farmaco.find("div", class_="product-block").findChildren("div", class_="image")[
        0].findChildren("div", class_="face")[0].findChildren("a")[0].findChildren("img")[0].get("src")

    k = requests.get(link, headers=headers).text
    dettagliFarmaco = BeautifulSoup(k, 'html.parser')

    minsan = dettagliFarmaco.find("span", {"itemprop": "sku"}).text
    descrizione = dettagliFarmaco.find(
        "div", {"itemprop": "description"}).findChildren("p")[1].text
    if descrizione == 'Principi attivi' or descrizione == 'Eccipienti' or descrizione == 'Indicazioni terapeutiche' or descrizione == 'Controindicazioni' or descrizione == 'Posologia' or descrizione == 'Avvertenze e precauzioni' or descrizione == 'Interazioni' or descrizione == 'Effetti indesiderati':
        descrizione = dettagliFarmaco.find(
            "div", {"itemprop": "description"}).findChildren("p")[2].text

    # parsificazione
    prezzoVecchio = prezzoVecchio.replace(',', '')
    prezzoNuovo = prezzoNuovo.replace(',', '')

    prezzoVecchio = prezzoVecchio.replace('€', '')
    prezzoNuovo = prezzoNuovo.replace('€', '')

    descrizione = descrizione.replace("\xa0", '')

    # inserimento delle informazioni in dizionario e poi lista
    informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzoNuovo': prezzoNuovo,
                           'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
    #informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
    # listaInformazioniFarmaci.append(informazioniFarmaco)
    # print(listaInformazioniFarmaci)

    print(nomeProdotto)

    # sezione critica
    lock.acquire()
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()


def invioRichieste(paginaIniziale, paginaFinale):
    pagina = paginaIniziale

    while pagina <= paginaFinale:
        k = requests.get(
            'https://farmacialoreto.it/farmaci?page=' + str(pagina), headers=headers).text
        soup = BeautifulSoup(k, 'html.parser')
        listaFarmaci = soup.find_all(
            "div", class_="col-lg-3 col-md-3 col-sm-6 col-xs-12")

        with ThreadPoolExecutor(max_workers=10) as executor:
            results = executor.map(scrapingProdotto, listaFarmaci)

        pagina = pagina + 1


def thread1():
    invioRichieste(1, 20)


def thread2():
    invioRichieste(21, 40)


def thread3():
    invioRichieste(41, 60)


def thread4():
    invioRichieste(61, 80)


def thread5():
    invioRichieste(81, 100)


def thread6():
    invioRichieste(101, 133)


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
