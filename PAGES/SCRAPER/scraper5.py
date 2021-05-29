import requests
from bs4 import BeautifulSoup
import pandas as pd

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

pagina = 1
listaFarmaci = []
listaInformazioniFarmaci = []
numeroPagine = 75

elemento = 0
while pagina < int(numeroPagine):
    # scorrimento delle pagine
    k = requests.get('https://www.farmacieravenna.com/7-farmaci#/page-' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("li", class_="ajax_block_product")
    
    for farmaco in listaFarmaci:
        nomeProdotto = farmaco.find("a", class_="product-name").text
        link = farmaco.find("a", class_="product-name").get("href")
        img = farmaco.find("img", class_="img-responsive").get("src")

        prezzoVecchio = farmaco.find("span", class_="old-price product-price")
        if prezzoVecchio == None:
            prezzoVecchio = -1
        else:
            prezzoVecchio = prezzoVecchio.text
        prezzoNuovo = farmaco.find("span", class_="price product-price").text

        k = requests.get(link).text
        dettagliFarmaco = BeautifulSoup(k,'html.parser')

        minsan = dettagliFarmaco.find("span", {"itemprop": "sku"}).text
        descrizione = dettagliFarmaco.find("div", {"itemprop": "description"}).text

        # parsificazione
        prezzoNuovo = prezzoNuovo.replace(" Prezzo  IVA inclusa € ", '')
        prezzoNuovo = prezzoNuovo.replace(" ", '')
        prezzoNuovo = prezzoNuovo.replace(",", '')

        if prezzoVecchio != -1:
            prezzoVecchio = prezzoVecchio.replace(" € ", '')
            prezzoVecchio = prezzoVecchio.replace(",", '')

        descrizione = descrizione.replace("\xa0", '')
        descrizione = descrizione.replace('\n', '')
        descrizione = descrizione.replace('                ', '')

        # inserimento delle informazioni in dizionario e poi lista
        # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
        informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
        listaInformazioniFarmaci.append(informazioniFarmaco)
        print(listaInformazioniFarmaci)

        elemento = elemento + 1
        print(elemento)

    pagina = pagina + 1