
/**
 * MAL Tracker using threading techniques
 * @author freedomofkeima
 * 
 */

public class AnimeModel {
	
	private int id;
	private String title;
	private String classification;
	private int rank;
	private double members_score;
	
	public AnimeModel() {
		id = -1; // initial
		title = "";
		classification = "";
		rank = 0;
		members_score = 0;
	}
	
	/** Auto generated getter & setter */
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getTitle() {
		return title;
	}
	public void setTitle(String title) {
		this.title = title;
	}
	public String getClassification() {
		return classification;
	}
	public void setClassification(String classification) {
		this.classification = classification;
	}
	public int getRank() {
		return rank;
	}
	public void setRank(int rank) {
		this.rank = rank;
	}
	public double getMembers_score() {
		return members_score;
	}
	public void setMembers_score(double members_score) {
		this.members_score = members_score;
	}
	
}
