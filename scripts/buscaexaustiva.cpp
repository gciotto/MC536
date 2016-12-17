#define cpp11 1

#include <cstdio>
#include <iostream>
#include <algorithm>
#include <fstream>
#include <vector>
#include <cmath>
#include <fstream>
#include <sstream>
#include <cstring>
#include <string>

#if cpp11 == 1
#include <chrono>
#endif

#define N_MAX 10100
#define K_MAX 75
#define D_MAX 2010
#define tam(x) ((int)(1+log10(x)))
#define INCORRECT_ARGUMENTS 1
#define MATRIX_NOT_FOUND 2
#define CANNOT_REMOVE_FILE 3

using namespace std;

/*int ap[] = {6, 866, 2004, 2371, 2404, 2453, 2482, 2577, 2984, 2995, 3434, 3445, 3620, 3660, 3662, 3674, 3706, 3729, 3796, 3808, 3828, 
	4237, 4448, 5794, 6471, 6613, 6775, 6795, 6798, 7114, 7704, 7904, 7932, 7966, 8040, 8569, 8685, 8790, 9216, 9561, 9609, 9680, 9688, 
	9718, 9732, 9755, 9795, 9830, 9859};*/

double pesquisa[N_MAX][D_MAX];
#if cpp11 == 1
std::chrono::time_point<std::chrono::system_clock> start, end2;
#endif

string zerofill(int, int);
string str2i(int, int);
string corrige(int);
double busca_distancia(double*, double*, int);
bool comp(pair<string,double>, pair<string,double>);
int pre_processamento(int, char**, int&, int&, int&, FILE*&);
void mede_tempo();

int main(int argc, char **argv) {
	int n, d, x, i;
	FILE *mtx, *vetorbuscado;
	if(pre_processamento(argc, argv, n, d, x, mtx))
		return 1;
	cout << "pre-processamento concluido" << endl;
	for(;;) {
		vetorbuscado = fopen("vetor_lido_exaustiva.nor", "r");
		if(!vetorbuscado)
			continue;
		cout << "imagem encontrada" << endl;
#if cpp11 == 1
		start = std::chrono::system_clock::now();
#endif
		double *vetor = new double[d+5];
		for(i = 0; i < d; i++)
			fscanf(vetorbuscado, " %lf", &(vetor[i]));
		fclose(vetorbuscado);
		vector< pair<string, double> > resultados(n);
		for(i = 0; i < n; i++) {
			resultados[i].first = "IMG" + str2i(i+1, 6) + ".jpg";
			resultados[i].second = busca_distancia(vetor, pesquisa[i], d);
		}
		sort(resultados.begin(), resultados.end(), comp);
		mede_tempo();
		ofstream out("vetor_lido.out");
		if(!out.is_open())
			return 1;
		out << x << endl;
		for(i = 0; i < x; i++) {
			out << resultados[i].first << " " << resultados[i].second << endl;
			cout << resultados[i].first << " " << resultados[i].second << endl;
		}
		out.close();
		mede_tempo();
		delete(vetor);
		resultados.clear();
	}
	return 0;
}

int pre_processamento(int argc, char **argv, int &n, int &d, int &x, FILE * &mtx) {
	if(argc != 5) {
		cout << "nomedoarquivo mtx[d] n d x";
		return INCORRECT_ARGUMENTS;
	}
	n = atoi(argv[2]);
	d = atoi(argv[3]);
	x = atoi(argv[4]);
	mtx = fopen(argv[1], "r");
	if(!mtx)
		return MATRIX_NOT_FOUND;
	int i;
	for(i = 0; i < n; i++)
		for(int j = 0; j < d; j++)
			fscanf(mtx, " %lf", &(pesquisa[i][j]));
	fclose(mtx);
	return 0;
}

string zerofill(int x, int tam) {
	string s;
	if(x)
		tam -= tam(x);
	else
		tam--;
	if(tam <= 0)
		return s;
	while(tam--)
		s += "0";
	return s;
}

string str2i (int  x, int tam) {
	ostringstream o;
	o << zerofill(x, tam) << x;
	return o.str();
}

/*string corrige(int num) {
	int cont = 0;
	for(int i = 0; i < sizeof(ap)/sizeof(int); i++)
		if(ap[i] <= num)
			cont++;
		else
			break;
	return "IMG" + str2i(num+cont, 6) + ".jpg";
}*/

double busca_distancia(double *x, double *y, int tam) {
	double total = 0;
	while(tam--)
		total += (x[tam] - y[tam])*(x[tam] - y[tam]);
	return total;
}

bool comp(pair<string, double>a, pair<string, double>b) {
	return a.second < b.second;
}

void mede_tempo() {
#if cpp11 == 1
   end2 = std::chrono::system_clock::now();
   cout << std::chrono::duration_cast<std::chrono::milliseconds> (end2-start).count() << "ms decorridos\n";
   start = end2;
#endif
}
