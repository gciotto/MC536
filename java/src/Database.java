import java.io.*;
import java.sql.*;


public class Database {
	
		private static Database instance = null;
		private Connection dbConnection = null;
		private int clients;
		
		private Database() throws ClassNotFoundException {
	        Class.forName("com.mysql.jdbc.Driver");
	        clients = 0;
	    }
		
		public static Database getInstance() throws IOException, ClassNotFoundException {
			if (instance == null)
	            instance = new Database();
	        return instance;
	    }
		
		public Connection getConnection() throws SQLException {
			 if (dbConnection == null)
				 dbConnection = DriverManager.getConnection("jdbc:mysql://titi.lab.ic.unicamp.br/mc53618", 
					 			"mc536user18", "aixaechu");
			clients++;
			return dbConnection;
		}
		
		public void releaseConnection() throws SQLException {
			 clients--;
			 if (dbConnection != null && clients <= 0) {
		         dbConnection.close();
		         dbConnection = null;
			 }
	    }
		
		public ResultSet executeQuery (String query) throws SQLException {
			Statement statement = getConnection().createStatement();

			ResultSet return_value = statement.executeQuery(query);

			//releaseConnection();
			//statement.close();
			
			return return_value;			
		}
		
		
		public int executePreparedQuery (String query, DataObject object) throws SQLException, FileNotFoundException {
			PreparedStatement statement = getConnection().prepareStatement(query);

			for (int i = 0; i < object.getCount(); i++) 
				switch (object.getCodeAt(i)) {
					case 0 : 	File f = (File)object.getInfoAt(i);
								statement.setBinaryStream(i+1, (InputStream) new FileInputStream(f), (int) f.length()); break;
					case 1 :	String s = (String) object.getInfoAt(i);
								statement.setString(i+1, s); break;
					case 2 :	Integer integer = (Integer) object.getInfoAt(i);
								statement.setInt(i+1, integer.intValue()); break;
					
				}
			
			
			int return_value = statement.executeUpdate();			
			statement.close();
			
			return return_value;			
		}
		
		public int executeCommand (String command) throws SQLException {
			Statement statement = getConnection().createStatement();

			int return_value = statement.executeUpdate(command);

			releaseConnection();
			statement.close();
			
			return return_value;			
		}
}
