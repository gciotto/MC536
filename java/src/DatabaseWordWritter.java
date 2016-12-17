import java.io.File;
import java.io.IOException;
import java.sql.ResultSet;
import java.util.Scanner;


public class DatabaseWordWritter {

	/**
	 * @param args
	 */
	public static void main(String[] args) throws IOException, ClassNotFoundException {
		
			int count = 0;
			Database instance = Database.getInstance();	
			File files = new File(DatabaseImageWritter.IMAGE_PATH);
			String name;
			
			for (File f : files.listFiles())
				try {
					if (f.isFile() && f.getName().contains(".tags")) {
						
						System.out.println(f.getName());
						
						name = f.getName().substring(0, f.getName().indexOf('.'));
						
						Scanner scanner = new Scanner(f);
						
						String word, query;
						ResultSet r;
						int id_imagem = 0, id_word = 0;
						
						while (scanner.hasNextLine()) {
							
							word = scanner.nextLine();
							
							/* Recupera imagem */
							query = "SELECT id_imagem FROM T_IMAGEM WHERE nome = '"+ name + "'";
							r = instance.executeQuery(query);
							
							if (r.next()) id_imagem = r.getInt(1);
							
							instance.releaseConnection();
							
							/* Verifica se já existe esta palavra no DB */
							query = "SELECT id_chave FROM T_PALAVRA_CHAVE WHERE texto = '"+ word +"'";
							
							r = instance.executeQuery(query);
							
							/* Insere se nao existe */
							String command;
							boolean isKey = false;
							if (!r.next()) {
								command = "INSERT INTO T_PALAVRA_CHAVE (texto) VALUES ('"+word+"')";
								instance.executeCommand(command);
								
								query = "SELECT id_chave FROM T_PALAVRA_CHAVE WHERE texto = '"+ word +"'";
								
								r = instance.executeQuery(query);
								isKey = true;
							}
							
							if (isKey) r.next(); 
							
							id_word = r.getInt(1); 
							
							instance.releaseConnection();
							instance.releaseConnection();
							
							command = "INSERT INTO T_IMAGEM_PALAVRA_CHAVE (id_imagem, id_chave) VALUES ("+
											id_imagem + "," +id_word+")";
							instance.executeCommand(command);
							
						}
					}
				} catch (Exception e) {
					System.err.println(e.getMessage());
			}
			System.out.println(count);
				
	}

}
