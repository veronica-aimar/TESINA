import requests
from bs4 import BeautifulSoup
import pandas as pd
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

pagina = 1
listaFarmaci = []
contatoreFarmaci = 1
listaInformazioniFarmaci = []

# lettura numero elementi
k = requests.get('https://www.amafarma.com/farmaci-da-banco.html').text
soup=BeautifulSoup(k,'html.parser')
farmaciTotali = int(soup.find("p", class_="toolbar-amount").findChildren()[2].text)

# scorrimento delle pagine
while contatoreFarmaci < farmaciTotali:
    k = requests.get('https://www.amafarma.com/farmaci-da-banco.html?p=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("li", class_="item product product-item")

    for farmaco in listaFarmaci:
        link = farmaco.find("a", class_="product photo product-item-photo").get('href')

        prezzi = farmaco.find_all("span", class_="price")
        prezzoVecchio = prezzi[0].text        
        if len(prezzi) > 1:
            prezzoNuovo = prezzi[1].text
        nomeProdotto = farmaco.find("strong", class_="product name product-item-name").findChildren("a")[0].text.strip()

        img = farmaco.find("span", class_="product-image-wrapper").findChildren("img")[0].get("src")

        k = requests.get(link).text
        dettagliSito=BeautifulSoup(k,'html.parser')

        minsan = dettagliSito.find("span", class_="sku").findChildren()[1].text
        dettagliGenerali = dettagliSito.find("div", class_="product attribute description")
        if dettagliGenerali != None:
            descrizione = dettagliGenerali.findChildren("div", class_="value")[0].text

        categoria = dettagliSito.find("span", class_="categoria_riferimento").findChildren("a")[0].text
        contatoreFarmaci = contatoreFarmaci + 1

        # parsificazione
        prezzoVecchio = prezzoVecchio.replace(',', '')
        prezzoNuovo = prezzoNuovo.replace(',', '')

        prezzoVecchio = prezzoVecchio.replace('€', '')
        prezzoNuovo = prezzoNuovo.replace('€', '')

        descrizione = descrizione.replace("\xa0", '')

        # inserimento delle informazioni in dizionario e poi lista
        informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': categoria}
        listaInformazioniFarmaci.append(informazioniFarmaco)

    pagina = pagina + 1

print(listaInformazioniFarmaci)