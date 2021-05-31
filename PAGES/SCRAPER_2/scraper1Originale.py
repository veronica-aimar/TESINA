import requests
import logging
from bs4 import BeautifulSoup
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
listaInformazioniFarmaci = []
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}


def scrapingProdotto(farmaco):
    link = farmaco.find(
        "a", class_="product photo product-item-photo").get('href')
    prezzi = farmaco.find_all("span", class_="price")
    prezzoVecchio = prezzi[0].text
    if len(prezzi) > 1:
        prezzoNuovo = prezzi[1].text
    nomeProdotto = farmaco.find(
        "strong", class_="product name product-item-name").findChildren("a")[0].text.strip()
    img = farmaco.find(
        "span", class_="product-image-wrapper").findChildren("img")[0].get("src")
    k = requests.get(link).text
    dettagliSito = BeautifulSoup(k, 'html.parser')
    minsan = dettagliSito.find("span", class_="sku").findChildren()[1].text
    dettagliGenerali = dettagliSito.find(
        "div", class_="product attribute description")

    if dettagliGenerali != None:
        descrizione = dettagliGenerali.findChildren(
            "div", class_="value")[0].text

    categoria = dettagliSito.find(
        "span", class_="categoria_riferimento").findChildren("a")[0].text

    # parsificazione
    prezzoVecchio = prezzoVecchio.replace(',', '')
    prezzoNuovo = prezzoNuovo.replace(',', '')

    prezzoVecchio = prezzoVecchio.replace('€', '')
    prezzoNuovo = prezzoNuovo.replace('€', '')

    descrizione = descrizione.replace("\xa0", '')
    prezzoVecchio = prezzoVecchio.replace("\xa0", '')
    prezzoNuovo = prezzoNuovo.replace("\xa0", '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': categoria}
    #informazioniFarmaco = [minsan, nomeProdotto, prezzoNuovo, prezzoVecchio, descrizione, img, categoria]

    # sezione critica
    # lock.acquire()
    informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzoNuovo': prezzoNuovo,
                           'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': categoria}
    print(informazioniFarmaco)
    return
    # lock.release()


def invioRichieste(pagina):
    # scorrimento delle pagine
    k = requests.get(
        'https://www.amafarma.com/farmaci-da-banco.html?p=' + str(pagina)).text
    soup = BeautifulSoup(k, 'html.parser')
    listaFarmaci = soup.find_all("li", class_="item product product-item")
    with ThreadPoolExecutor(max_workers=1) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)


def main():
    numeroPagine = 84
    listaNumeroPagine = []
    for x in range(1, numeroPagine):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=1) as executor:
        results = executor.map(invioRichieste, '1')


if __name__ == '__main__':
    main()
