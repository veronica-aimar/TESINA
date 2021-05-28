import requests
from bs4 import BeautifulSoup
import pandas as pd
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

pagina = 1
listaFarmaci = []
pagineTotali = 201
listaInformazioniFarmaci = []

# scorrimento delle pagine
while pagina < pagineTotali:
    k = requests.get('https://www.topfarmacia.it/3366-farmaci-da-banco?page=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("article", class_="product-miniature product-miniature-default product-miniature-grid product-miniature-layout-1 js-product-miniature")

    for farmaco in listaFarmaci:
        link = farmaco.find("div", class_="thumbnail-container").findChildren("a")[0].get('href')
        minsan = farmaco.find("div", class_="product-reference text-muted").findChildren("a")[0].text
        prezzoNuovo = farmaco.find("span", class_="product-price").text
        if farmaco.find("span", class_="regular-price text-muted") != None:
            prezzoVecchio = farmaco.find("span", class_="regular-price text-muted").text
        
        nomeProdotto = farmaco.find("h3", class_="h3 product-title").findChildren("a")[0].text
        img = farmaco.find("div", class_="thumbnail-container").findChildren("img")[0].get("data-src")
        categoria = farmaco.find("div", class_="product-category-name text-muted").text

        k = requests.get('https://www.topfarmacia.it/3366-farmaci-da-banco?page=' + str(pagina)).text
        dettagliFarmaco = BeautifulSoup(k,'html.parser')
        # descrizione = dettagliFarmaco.find("div", class_="col-md-6 col-product-info")

        # parsificazione
        prezzoVecchio = prezzoVecchio.replace(',', '')
        prezzoNuovo = prezzoNuovo.replace(',', '')

        prezzoVecchio = prezzoVecchio.replace('€', '')
        prezzoNuovo = prezzoNuovo.replace('€', '')

        #descrizione = descrizione.replace("\xa0", '')

        # inserimento delle informazioni in dizionario e poi lista
        informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': 'descrizione', 'img': img, 'categoria': categoria}
        listaInformazioniFarmaci.append(informazioniFarmaco)
        #print(informazioniFarmaco)

    pagina = pagina + 1