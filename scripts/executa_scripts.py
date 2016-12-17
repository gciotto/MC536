import os
import sys

script_path = "scripts/"
vetor_path = "vetores/"

relative_name = sys.argv[1]
absolute_name = relative_name [7:]

command = "python "+script_path+"caracterizar.py "+ relative_name +" "+ vetor_path + absolute_name[:-4]+".key"
print(command)
os.system(command)

command = "python "+script_path+"projeta.py "+ vetor_path + absolute_name[:-4]+".key scripts/dicionario.pal " + vetor_path + absolute_name[:-4]
print(command)
os.system(command)

command = "python "+script_path+"normalizar.py "+ vetor_path + absolute_name[:-4]+".hist " + vetor_path + absolute_name[:-4]
print(command)
os.system(command)


command = "rm -f vetores/"+absolute_name[:-4]+".key  vetores/"+absolute_name[:-4]+".hist"
print(command)
os.system(command)


