import java.io.File;

public class DataObject {
	
	private int codes[];
	private Object info[];
	private int count = 0;
	
	public DataObject (int c) {
		count = c;
		codes = new int[count];
		info = new Object[count];
	}
	
	public int getCount() {
		return count;
	}
	
	public Object getInfoAt(int index) {
		return info[index];
	}
	
	public int getCodeAt(int index) {
		return codes[index];
	}
	
	public void setInfoAt(int index, Object o) {
		info[index] = o;
	}
	
	public void setCodeAt(int index, int i) {
		codes[index] = i;
	}
	
}
	
