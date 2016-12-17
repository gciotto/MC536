#!/bin/bash


i=1

for nome in *.jpg
do
 echo "python caracterizar.py  $nome  vetor_$i" 
 python -c 'import sys' caracterizar.py  $nome  vetor_$i 

 i=$(($i + 1)) 

done

    

