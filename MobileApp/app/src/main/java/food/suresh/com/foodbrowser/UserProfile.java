package food.suresh.com.foodbrowser;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import food.suresh.com.foodbrowser.reviews.ReviewData;

public class UserProfile extends AppCompatActivity {

    private ProgressDialog pDialog;
    private RecyclerView rv;
    private ReviewAdapter radapter;
    public List<ReviewData> data;

    TextView points;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_profile);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);

        CollapsingToolbarLayout collapsingToolbarLayout = (CollapsingToolbarLayout) findViewById(R.id.toolbar_layout);
        TextView email = (TextView) findViewById(R.id.emailadd);
        points = (TextView) findViewById(R.id.points);

        setSupportActionBar(toolbar);

        SharedPreferences sp = getSharedPreferences("Login", 0);
        String user = sp.getString("username", null);
        String name = sp.getString("fullname", null);
        String uid = sp.getString("uid", null);

        collapsingToolbarLayout.setTitle(name);
        email.setText(user);

        rv = (RecyclerView)findViewById(R.id.profreviewlist);

        new LoadDetails().execute();
    }

    private class LoadDetails extends AsyncTask<Void, Void, Void> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(UserProfile.this);
            pDialog.setMessage("Loading Details");
            pDialog.setCancelable(false);
            pDialog.setCanceledOnTouchOutside(false);
            pDialog.show();
        }

        @Override
        protected Void doInBackground(Void... arg0) {

            //String uid = arg0[0];

            GetJSON getJSON = new GetJSON();

            String url = "https://thumbsup.ml/client.php?task=getprofile&uid=1";

            String jsonStr = getJSON.getJSON(url,5000);

            data=new ArrayList<>();

            if (jsonStr != null) {
                try {

                    JSONObject jsonObj = new JSONObject(jsonStr);
                    JSONArray reviews = jsonObj.getJSONArray("reviews");

                    final String totpoints = jsonObj.getString("totalpoints");

                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            points.setText(totpoints+" POINTS");
                        }
                    });

                    for (int i = 0; i < reviews.length(); i++) {

                        JSONObject c = reviews.getJSONObject(i);

                        String uname = c.getString("name");
                        String quality = c.getString("overall");
                        String taste = c.getString("overall");
                        String price = c.getString("overall");
                        String description = c.getString("description");

                        ReviewData rdata = new ReviewData();

                        rdata.uname = uname;
                        rdata.quality = quality;
                        rdata.taste = taste;
                        rdata.price = price;
                        rdata.description = description;

                        data.add(rdata);
                    }

                } catch (final JSONException e) {

                    Log.e("Json parsing error: ",e.getMessage());
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Toast.makeText(getApplicationContext(), "Json parsing error: " + e.getMessage(),Toast.LENGTH_LONG).show();
                        }
                    });

                }

            } else {

                Log.e("Error","Couldn't get json from server.");
                UserProfile.this.runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        Toast.makeText(getApplicationContext(), "Couldn't get json from server. Check LogCat for possible errors!", Toast.LENGTH_LONG).show();
                    }
                });

            }

            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            radapter = new ReviewAdapter(UserProfile.this,data);
            rv.setAdapter(radapter);
            rv.setLayoutManager(new LinearLayoutManager((UserProfile.this)));
            pDialog.cancel();
        }
    }
}
