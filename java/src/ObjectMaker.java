import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.util.Scanner;


public class ObjectMaker {
	
	public static int BLOB = 0, STRING = 1, INT = 2;
	
	public static DataObject giveObject(String name) throws IOException {
		DataObject data = new DataObject(1);
		
		data.setCodeAt(0, BLOB);
		data.setInfoAt(0, new File(DatabaseImageWritter.IMAGE_PATH+name+".jpg"));
		
		/*
		StringBuilder sb = new StringBuilder();
		Reader r = new FileReader(DatabaseImageWritter.IMAGE_PATH+name+".desc");
        int c = 0;
        while (r.ready()) {
            c = r.read();
            sb.append((char) c);
        }
		data.setCodeAt(1, STRING);
		data.setInfoAt(1, sb.toString());
		
		
		data.setCodeAt(2, STRING);
		data.setInfoAt(2, name);
        */
		return data;
	}

}
