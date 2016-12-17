#define cpp11

#include <cstdio>
#include <iostream>
#include <algorithm>
#include <fstream>
#include <vector>
#include <cmath>
#include <fstream>
#include <sstream>
#include <cstring>

#ifdef cpp11
#include <chrono>
#endif

/*o valor de k utilizado é floor(sqrt(n/2)). Para n=5,000, k=50. Para n=10,000, k=70. Para que k se altere com a inserção de imagens (considerando que já tenhamos 10,000 no banco),
então floor(sqrt(n/2))=71. O menor valor de n para que tenhamos 71 centroides é 10,082. Portanto, deveremos inserir pelo menos 82 imagens para que tenhamos de rebalancear os centroides.
Após a inserção de 82 imagens, o próximo número de inserções para que haja o rebalanceamento é de 286 imagens. */

#define N_MAX 10100
#define K_MAX 100
#define D_MAX 2010
#define TR_MAX 5
#define tam(x) ((int)(1+log10(x)))
#define threshold (TR_MAX < d ? TR_MAX : d)

using namespace std;

#ifdef cpp11
std::chrono::time_point<std::chrono::system_clock> start, end2;
#endif

void mede_tempo();

string zerofill(int, int);

string str2i(int, int);

double busca_distancia(double*, double*, int);

double cent[K_MAX][D_MAX], vetor[D_MAX];

int pre_processamento(int, char**);

int k, d, x;

int main(int argc, char **argv) {

#ifdef cpp11
	start = std::chrono::system_clock::now();
#endif
	if(pre_processamento(argc, argv)) {
		cout << "Fatal error" << endl;
		return true;
	}
	cout << "Pre-processamento concluido" << endl;

	mede_tempo();

	int i, j;

	vector< pair<double,int> > bsc(k);
	for(i = 0; i < k; i++)
		bsc[i] = make_pair(busca_distancia(cent[i], vetor, d), i);
	partial_sort(bsc.begin(), bsc.begin() + x, bsc.end()); /*quais são os x centroides mais perto?*/
	mede_tempo();

	vector< pair<double, char*> > busca;
	string s;
	for(i = 0; i < threshold; i++) {
		s.clear();
		for(j = 0; ; j++) {
			if(caminho[j] == '\0')
				break;
			s += caminho[j];
		}
		s += "centroides_" + str2i(d, 4) + "_" + str2i(bsc[i].second, 3) + ".txt";
		C = fopen(s.c_str(), "r");
		if(!C)
			return 1;
		int qtde;
		fscanf(C, "%d\n", &qtde);
		for(int j = 0; j < qtde; j++) {
			char *string_aux = new char[50];
			fscanf(C, " %s", string_aux);
			double *vetor_comp = new double[d+5];
			for(int k = 0; k < d; k++)
				scanf("%lf", &vetor_comp[k]);
			busca.push_back( make_pair(busca_distancia(vetor_comp, vetor, d), string_aux) );
			delete vetor_comp;
			delete string_aux;
		}
	}

	mede_tempo(); /*a busca terminou*/
	partial_sort(busca.begin(), busca.begin()+x, busca.end()); /*quais são as x imagens mais próximas?*/
	ofstream img(argv[3]);
	img << x << endl; /*as escreva na saída*/
	for(i = 0; i < x; i++)
		img << busca[i].second << endl;
	img.close();
	cout << x << endl;
	for(i = 0; i < x; i++)
		cout << busca[i].second << " " << busca[i].first << endl;
	mede_tempo();
	return 0;
}

int pre_processamento(int argc, char **argv) {
	if(argc != 6) {
		cout << "nomedoarquivo [C][d].txt k d x (caminho para os centroides)" << endl;
		return 1;
	}
	k = atoi(argv[2]);
	d = atoi(argv[3]);
	x = atoi(argv[4]);
	ifstream C(argv[1]);
	if(!C.is_open())
		return 1;
	int i, j;
	for(i = 0; i < k; i++)
		for(j = 0; j < d; j++)
			C >> cent[i][j];
	C.close();
	ifstream vb(argv[2]);
	for(i = 0; i < d; i++)
		vb >> vetor[i];
	vb.close();
	return 0;
}

void mede_tempo() {
#ifdef cpp11
	end2 = std::chrono::system_clock::now();
	cout << std::chrono::duration_cast<std::chrono::milliseconds> (end2-start).count();
	start = end2;
#endif
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

string str2i (int x, int tam) {
	ostringstream o;
	o << zerofill(x, tam) << x;
	return o.str();
}

double busca_distancia(double *x, double *y, int tam) {
	double total = 0;
	while(tam--)
		total += (x[tam] - y[tam])*(x[tam] - y[tam]);
	return total;
}
