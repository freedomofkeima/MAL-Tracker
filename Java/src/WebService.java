import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.ConnectException;
import java.net.URL;
import java.net.URLConnection;
import java.net.UnknownHostException;
import java.util.concurrent.Callable;

/**
 * MAL Tracker using threading techniques
 * @author freedomofkeima
 *
 */

public class WebService implements Callable<String> {
	
	private int id = -1;
	
	public WebService(int id) {
		this.id = id;
	}
	
	@Override
	public String call() throws Exception  {
		URLConnection connection;
		try {
			URL url = new URL("http://mal-api.com/anime/" + Integer.toString(id) + "?format=json");
			connection = url.openConnection();
			String line;
			StringBuilder builder = new StringBuilder();
			
			try {
				BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
				while((line = reader.readLine()) != null) {
					builder.append(line);
				}
				
				return builder.toString();
			} catch (ConnectException e) {
				System.out.println("Exception : Connection of id = " + id + " has failed (TimeOut).");
			} catch (FileNotFoundException e) {
				System.out.println("File of id = " + id + " is not found.");
				String retPageNotFound = "{\"id\":" + Integer.valueOf(id) + "}";
				return retPageNotFound; // actual page is not exist
			}
			
		} catch (UnknownHostException e) {
			System.out.println("Exception : Connection of id = " + id + " has failed (Host Unknown).");
		} catch (IOException e) {
			System.out.println("Exception : Connection of id = " + id + " has failed (IO Exception).");
		}
		
		return "{}";
	}
	
}
