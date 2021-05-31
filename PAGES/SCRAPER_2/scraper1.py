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

listaInformazioniFarmaci = []


def main():
    numeroPagine = 84
    pagina = 0

    while pagina < numeroPagine:
        k = requests.get(
            'https://www.amafarma.com/farmaci-da-banco.html?p=' + str(pagina)).text
        soup = BeautifulSoup(k, 'html.parser')
        listaFarmaci = soup.find_all("li", class_="item product product-item")
        for farmaco in listaFarmaci:
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
            minsan = dettagliSito.find(
                "span", class_="sku").findChildren()[1].text
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
            listaInformazioniFarmaci.append(informazioniFarmaco)

        pagina = pagina + 1

    for informazioniFarmaco in listaInformazioniFarmaci:
        print(informazioniFarmaco)


if __name__ == '__main__':
    main()
