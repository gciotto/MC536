#!/usr/local/bin/python
# coding: utf-8

import sys
import string
	

def normalizar(vetorIN, vetorOUT):
	arquivoHistogramas = open(vetorIN, "rb")
	arquivoSaida = open(vetorOUT + ".nor", "wb")
	lines = arquivoHistogramas.readlines()

	for line in lines:
		v = ""
		count = 0
		j = string.split(line)
		label = j[len(j) - 1]
		for i in range(0, len(j) - 1):
			count = count + int(j[i])
		for i in range(0, len(j) - 1):
			v = v + " " + str((float(j[i])/count))
		arquivoSaida.write(v + " " + label + "\n")
	arquivoSaida.close()
	arquivoHistogramas.close()	

#################################################################

vetorIN=sys.argv[1]
vetorOUT=sys.argv[2]
normalizar(vetorIN,vetorOUT)
