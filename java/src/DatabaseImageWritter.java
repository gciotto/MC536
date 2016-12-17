import java.io.*;
import java.sql.ResultSet;
import java.util.Scanner;


public class DatabaseImageWritter {

	public static String IMAGE_PATH = "/var/www/Trabalho/images/";
	public static String ARRAY_PATH = "/home/gustavo/Desktop/UNICAMP Projects/MC536/vetores/";
	public static String NORM_PATH = "/home/gustavo/Desktop/UNICAMP Projects/MC536/normalizados/";
	
	public static void main (String args[]) throws IOException, ClassNotFoundException {
			int count = 0;
			Database instance = Database.getInstance();	
			File files = new File(DatabaseImageWritter.IMAGE_PATH);
	
			for (File f : files.listFiles())
				try {
					if (f.isFile() && f.getName().contains(".jpg")) {
						
						System.out.println(f.getName());
						
						String name = f.getName().substring(0, f.getName().indexOf('.'));
						
						DataObject data = ObjectMaker.giveObject(name);
						/*instance.executeCommand("INSERT INTO T_IMAGEM(descricao, vetor, imagem) VALUES ('"+
										data.getDescricao()+"','"+data.getVetor_hist()+"','"+data.getImagem()+"')");*/
						instance.executePreparedQuery("UPDATE T_IMAGEM SET imagem = (?) WHERE nome = '"+name+"';",
																	data);
						count++;
					}
				} catch (Exception e) {
					System.err.println(e.getMessage());
			}
			System.out.println(count);
				
	}
}
