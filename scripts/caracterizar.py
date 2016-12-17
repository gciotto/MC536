#!/usr/local/bin/python
# coding: utf-8

import sys
import os
import Image
import string

def mountSpecialName(nameImage):
	j = 0
	name = ""
	while True:
		c = nameImage[j:j+1]
		if (c == " "):
			name = name + "\ "
		elif (c == "("):
			name = name + "\("
		elif (c == ")"):
			name = name + "\)"
		elif (c == "."):
			break
		else:
			name = name + c
		j = j + 1
	return name

def organizeFileSurfToDescriptor(nfile):	 # changes the .key format
	arqin = open(nfile,"rb")	
	tamanho = arqin.readline()
	#print tamanho
	tamanho = int(tamanho) -1		# dimensions (64 - 128)
	#print tamanho
	totalPontos = int(arqin.readline())	# points found
	linha = str(totalPontos) + " " + str(tamanho) + " \n"
	out = []
	out.append(linha)
	linhas = arqin.readlines()
	for i in linhas:
		lin = i
		lin = lin.split(" ")
		cont = 0
		posicao = ""
		descritor = ""
		for j in lin:
			if (cont < 5):		# x y a b c
				posicao = posicao + str(j) + " "
			elif (cont == 6):	# l (laplacian)
				posicao = posicao + "\n"
				descritor = descritor + " " + str(j)
			elif (cont > 6):	# features vector
				descritor = descritor + " " + str(j)				
			cont = cont + 1
		out.append(posicao)
		out.append(descritor)
	arqin.close()
	nout = nfile
	arqout = open(nout,"wb")
	arqout.seek(0)
	for o in out:
		arqout.write(o)
	arqout.close()
	

def caracterizarImagem(dirImage,vetor):
	comando = "convert " + dirImage + " " + dirImage[:-4] + ".pgm"
	os.system(comando)
	nnome = mountSpecialName(dirImage) + ".pgm"
	nout =  vetor + ".key"
	nout = mountSpecialName(nout) + ".key"
	comando='./surf.ln -i ' + nnome + " -o " + nout + " -e -thres 3500 -d" #128 dim. retirar -e para 64 dim
	os.system(comando)
	os.system("rm " + dirImage[:-4] + ".pgm")	
	organizeFileSurfToDescriptor(nout.replace(".pgm",".key"))
	

#################################################################

nomeIN=sys.argv[1]
nomeOUT=sys.argv[2]
caracterizarImagem(nomeIN,nomeOUT)


