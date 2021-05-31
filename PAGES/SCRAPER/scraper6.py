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

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}


def scrapingProdotto(farmaco):
    nomeProdotto = farmaco.find(
        "h3", class_="h3 product-title").findChildren("a")[0].text
    img = farmaco.find("img", class_="js-lazy-product-image").get("data-src")

    prezzoVecchio = farmaco.find("span", class_="regular-price")
    if prezzoVecchio != None:
        prezzoVecchio = prezzoVecchio.text
    else:
        prezzoVecchio = -1
    prezzoNuovo = farmaco.find("span", class_="product-price").text

    minsan = farmaco.find("div", class_="product-reference text-muted").text
    descrizione = farmaco.find("div", class_="product-description-short")
    if descrizione != None:
        descrizione = descrizione.text
    else:
        descrizione = ''

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
                           'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
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
            'https://farmaciasemplice.it/2085544-farmaci?page=' + str(pagina), headers=headers).text
        soup = BeautifulSoup(k, 'html.parser')
        listaFarmaci = soup.find_all(
            "div", class_="js-product-miniature-wrapper")

        with ThreadPoolExecutor(max_workers=10) as executor:
            results = executor.map(scrapingProdotto, listaFarmaci)

        pagina = pagina + 1


def thread1():
    invioRichieste(1, 31)


def thread2():
    invioRichieste(32, 62)


def thread3():
    invioRichieste(63, 93)


def thread4():
    invioRichieste(94, 124)


def thread5():
    invioRichieste(125, 156)


def thread6():
    invioRichieste(157, 189)


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
