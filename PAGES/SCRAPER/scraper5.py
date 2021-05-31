import requests
from bs4 import BeautifulSoup
import pandas as pd
import concurrent.futures
from concurrent.futures import ThreadPoolExecutor
import threading
import schedule
import time

from requests.api import head
from ManagerFarmaci import inserimentoDB

lock = threading.Lock()
listaInformazioniFarmaci = []

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}


def scrapingProdotto(farmaco):
    nomeProdotto = farmaco.find(
        "h3", class_="h3 product-title").findChildren("a")[0].text
    link = farmaco.find(
        "h3", class_="h3 product-title").findChildren("a")[0].get("href")
    img = farmaco.find("div", class_="thumbnail-container").findChildren("a")[
        0].findChildren("img")[0].get("data-src")

    prezzoVecchio = farmaco.find("span", class_="regular-price text-muted")
    if prezzoVecchio == None:
        prezzoVecchio = -1
    else:
        prezzoVecchio = prezzoVecchio.text
    prezzoNuovo = farmaco.find("span", class_="product-price").text

    k = requests.get(link, headers=headers).text
    dettagliFarmaco = BeautifulSoup(k, 'html.parser')

    minsan = dettagliFarmaco.find("span", {"itemprop": "sku"}).text

    descrizione = dettagliFarmaco.find("div", {"id": "description"})
    if descrizione == None:
        descrizione = 'Descrizione da modificare'
    else:
        descrizione = descrizione.findChildren(
            "div", class_="rte-content")[0].text

    # parsificazione
    prezzoNuovo = prezzoNuovo.replace("€", '')
    prezzoNuovo = prezzoNuovo.replace(",", '')

    if prezzoVecchio != -1:
        prezzoVecchio = prezzoVecchio.replace("€", '')
        prezzoVecchio = prezzoVecchio.replace(",", '')

    descrizione = descrizione.replace("\xa0", '')
    descrizione = descrizione.replace("\n", '')

    # inserimento delle informazioni in dizionario e poi lista
    informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzoNuovo': prezzoNuovo,
                           'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmaci da banco'}
    # informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']

    print(nomeProdotto)
    print(prezzoVecchio)
    print(prezzoNuovo)
    # sezione critica
    lock.acquire()
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()


def invioRichieste(paginaIniziale, paginaFinale):
    # scorrimento delle pagine
    pagina = paginaIniziale

    while pagina <= paginaFinale:
        k = requests.get(
            'https://farmaciasavorani.it/farmaci/?page=' + str(pagina), headers=headers).text
        soup = BeautifulSoup(k, 'html.parser')
        listaFarmaci = soup.find_all(
            "div", class_="js-product-miniature-wrapper")

        with ThreadPoolExecutor(max_workers=10) as executor:
            results = executor.map(scrapingProdotto, listaFarmaci)

        pagina = pagina + 1


def thread1():
    invioRichieste(1, 6)


def thread2():
    invioRichieste(7, 12)


def thread3():
    invioRichieste(13, 18)


def thread4():
    invioRichieste(19, 24)


def thread5():
    invioRichieste(25, 30)


def thread6():
    invioRichieste(31, 36)


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
