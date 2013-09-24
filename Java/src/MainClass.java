import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * MAL Tracker using threading & auto re-connect techniques
 * 
 * @author freedomofkeima
 * 
 */

public class MainClass {

	private static final int NTHREADS = 30; // default number of threads

	public static void main(String Args[]) {
		long startTime = System.currentTimeMillis();
		int upperId = 21000;
		int lowerId = 1;
		int count = 0;

		ExecutorService executor = Executors.newFixedThreadPool(NTHREADS);
		List<Future<String>> list = new ArrayList<Future<String>>();
		AnimeModel savePoint[] = new AnimeModel[upperId + 1];

		for (int i = 0; i < upperId + 1; i++) {
			savePoint[i] = new AnimeModel();
			savePoint[i].setTitle(""); // initialize
		}
		
		while (count != upperId - lowerId + 1) {
			count = 0; // reset count model
			for (int i = lowerId; i <= upperId; i++) {
				if (savePoint[i].getId() == -1) {
					Callable<String> worker = new WebService(i);
					Future<String> submit = executor.submit(worker);
					list.add(submit);
				}
			}

			for (Future<String> future : list) {
				try {
					JSONObject json;
					try {
						json = new JSONObject(future.get());
						/** Write info to AnimeModel */
						if (savePoint[json.getInt("id")].getId() != json.getInt("id")) {
							savePoint[json.getInt("id")].setId(json.getInt("id"));
							savePoint[json.getInt("id")].setTitle(json
									.getString("title"));
							savePoint[json.getInt("id")].setClassification(json
									.getString("classification"));
							savePoint[json.getInt("id")].setRank(json.getInt("rank"));
							savePoint[json.getInt("id")].setMembers_score(json
									.getDouble("members_score"));	
							System.out.println("File with id = " + String.valueOf(json.getInt("id")) + " is found.");
						}
					} catch (JSONException e1) {
						// DO NOTHING
					}
				} catch (InterruptedException e) {
					e.printStackTrace();
				} catch (ExecutionException e) {
					e.printStackTrace();
				}
			}
			
			for (int i = lowerId; i <= upperId; i++) // ensuring every entries correctness
				if (savePoint[i].getId() != -1) count++;
			
			list.clear(); // reset list
		}

		FileWriter output = null;
		try {
			output = new FileWriter("../MAL_database_extract-test.txt");
			BufferedWriter writer = new BufferedWriter(output);
			for (int i = 1; i < upperId + 1; i++) {
				if (savePoint[i].getId() != -1) { // write to file
					writer.write(Integer.toString(savePoint[i].getId())
							+ ",\"" + savePoint[i].getTitle() 
							+ "\",\"" + savePoint[i].getClassification() 
							+"\"," + Integer.toString(savePoint[i].getRank())
							+ "," + Double.toString(savePoint[i].getMembers_score()));
					writer.newLine();
					/** if title == "", then there's a connection problem while retrieving */
				}
			}
			writer.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
		
		long endTime = System.currentTimeMillis();
		System.out.println("Finished in " + Math.floor((double) (endTime - startTime) / 1000) + " s");
		executor.shutdown();
	}

}
