package food.suresh.com.foodbrowser;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import food.suresh.com.foodbrowser.R;
import food.suresh.com.foodbrowser.reviews.OfferData;

public class Rewards extends AppCompatActivity {

    private ProgressDialog pDialog;
    private RecyclerView rv;
    private OfferAdapter radapter;
    public List<OfferData> data;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_rewards);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        rv = (RecyclerView)findViewById(R.id.offerlist);

        new LoadDetails().execute();
    }

    private class LoadDetails extends AsyncTask<Void, Void, Void> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(Rewards.this);
            pDialog.setMessage("Loading Details");
            pDialog.setCancelable(false);
            pDialog.setCanceledOnTouchOutside(false);
            pDialog.show();
        }

        @Override
        protected Void doInBackground(Void... arg0) {

            GetJSON getJSON = new GetJSON();

            String url = "https://thumbsup.ml/client.php?task=offerslist";

            String jsonStr = getJSON.getJSON(url, 5000);

            data = new ArrayList<>();

            if (jsonStr != null) {
                try {

                    JSONObject jsonObj = new JSONObject(jsonStr);
                    JSONArray reviews = jsonObj.getJSONArray("offers");

                    for (int i = 0; i < reviews.length(); i++) {

                        JSONObject c = reviews.getJSONObject(i);

                        String offname = c.getString("name");
                        String description = c.getString("description");
                        String points = c.getString("requiredpoints");

                        OfferData rdata = new OfferData();

                        rdata.offername = offname;
                        rdata.description = description;
                        rdata.points = points;

                        data.add(rdata);
                    }

                } catch (final JSONException e) {

                    Log.e("Json parsing error: ", e.getMessage());
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Toast.makeText(Rewards.this, "Json parsing error: " + e.getMessage(), Toast.LENGTH_LONG).show();
                        }
                    });

                }

            } else {

                Log.e("Error", "Couldn't get json from server.");
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        Toast.makeText(Rewards.this, "Couldn't get json from server. Check LogCat for possible errors!", Toast.LENGTH_LONG).show();
                    }
                });

            }

            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            radapter = new OfferAdapter(Rewards.this, data);
            rv.setAdapter(radapter);
            rv.setLayoutManager(new LinearLayoutManager((Rewards.this)));
            pDialog.cancel();
        }
    }
}
