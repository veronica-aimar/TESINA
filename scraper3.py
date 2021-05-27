import requests
from bs4 import BeautifulSoup
import pandas as pd
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

pagina = 1
listaFarmaci = []
arrayLink = []
contatoreFarmaci = 1
pagineTotali = 133

# scorrimento delle pagine
while pagina < pagineTotali:
    k = requests.get('https://farmacialoreto.it/farmaci?page=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("div", class_="col-lg-3 col-md-3 col-sm-6 col-xs-12")
    
    for farmaco in listaFarmaci:
        link = farmaco.find("div", class_="image").findChildren("div", classm_="face").findChildren("a").get("href")
        minsan = farmaco.find("div", {"itemprop": "sku"}).text
        prezzoNuovo = farmaco.find("span", class_="price-new").text
        if farmaco.find("span", class_="price-old") != None:
            prezzoVecchio = farmaco.find("span", class_="price-old").text
        
        nomeProdotto = farmaco.find("h3", class_="name").findChildren("a")[0].text
        img = farmaco.find("div", class_="image").findChildren("div", classm_="face").findChildren("a").findChildren("img").get("src")
        categoria = farmaco.find("div", {"itemprop": "description"}).findChildren("h2").text

        k = requests.get('https://www.topfarmacia.it/3366-farmaci-da-banco?page=' + str(pagina)).text
        dettagliFarmaco = BeautifulSoup(k,'html.parser')
        descrizione = dettagliFarmaco.find("div", {"itemprop": "description"}).findChildren("p")[0].text

    pagina = pagina + 1