import requests
from bs4 import BeautifulSoup
import pandas as pd

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

pagina = 1
listaFarmaci = []
listaInformazioniFarmaci = []

k = requests.get('https://www.farmaciadifiducia.com/it/farmaci-da-banco-on-line/').text
soup=BeautifulSoup(k,'html.parser')
pagineTotali = soup.find("a", class_="last").text

while pagina < int(pagineTotali):
    # scorrimento delle pagine
    k = requests.get('https://www.farmaciadifiducia.com/it/farmaci-da-banco-on-line/?p=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("li", class_="item")

    for farmaco in listaFarmaci:
        nomeProdotto = farmaco.find("h2", class_="product-name").text
        link = farmaco.find("h2", class_="product-name").findChildren("a")[0].get("href")
        img = farmaco.find("a", class_="product-image").findChildren("img")[0].get("src")
        descrizione = farmaco.find("div", class_="desc std").text

        prezzoVecchio = farmaco.find("span", class_="price")
        if prezzoVecchio == None:
            prezzoVecchio = -1
        else:
            prezzoVecchio = prezzoVecchio.text
        prezzoNuovo = farmaco.find("p", class_="special-price").findChildren("span", class_="price")[0].text

        k = requests.get(link).text
        dettagliFarmaco = BeautifulSoup(k,'html.parser')
        titolo = dettagliFarmaco.find("dl", class_="farma-details").findChildren("dt")[0].text
        if titolo == 'Sku: ':
            minsan = dettagliFarmaco.find("dl", class_="farma-details").findChildren("dd")[0].text
        else:
            minsan = dettagliFarmaco.find("dl", class_="farma-details").findChildren("dd")[1].text
        
        # parsificazione
        prezzoVecchio = prezzoVecchio.replace(',', '')
        prezzoNuovo = prezzoNuovo.replace(',', '')

        prezzoVecchio = prezzoVecchio.replace('€', '')
        prezzoNuovo = prezzoNuovo.replace('€', '')

        descrizione = descrizione.replace("\xa0", '')
        descrizione = descrizione.replace('\n', '')
        descrizione = descrizione.replace('                ', '')

        # inserimento delle informazioni in dizionario e poi lista
        # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
        informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
        listaInformazioniFarmaci.append(informazioniFarmaco)
        print(listaInformazioniFarmaci)

    pagina = pagina + 1