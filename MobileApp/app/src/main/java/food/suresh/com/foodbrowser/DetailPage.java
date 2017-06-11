package food.suresh.com.foodbrowser;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;
import food.suresh.com.foodbrowser.reviews.ReviewData;
import me.zhanghai.android.materialratingbar.MaterialRatingBar;

public class DetailPage extends AppCompatActivity {

    private ProgressDialog pDialog;
    private RecyclerView rv;
    private ReviewAdapter radapter;
    public List<ReviewData> data;

    public TextView pricedisp;
    public TextView descdisp;
    public CollapsingToolbarLayout collapsingToolbarLayout;

    public TextView ratprice;
    public TextView ratquality;
    public TextView ratteste;
    public TextView ratfull;
    public MaterialRatingBar matratbar;

    public String itemID;
    public String randNo;

    Button btnpurchase;
    Button btnrate;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_page);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        randNo = getIntent().getStringExtra("qrCode");

        pricedisp = (TextView) findViewById(R.id.itemprice);
        descdisp = (TextView) findViewById(R.id.itemdesc);
        collapsingToolbarLayout = (CollapsingToolbarLayout) findViewById(R.id.toolbar_layout);
        rv = (RecyclerView)findViewById(R.id.reviewslist);
        btnrate = (Button) findViewById(R.id.btnrate);
        btnpurchase = (Button) findViewById(R.id.btnpurchase);

        ratprice = (TextView) findViewById(R.id.price_rat);
        ratquality = (TextView) findViewById(R.id.quality_rat);
        ratteste = (TextView) findViewById(R.id.taste_rat);
        ratfull = (TextView) findViewById(R.id.full_rat);
        matratbar = (MaterialRatingBar) findViewById(R.id.currrating);

        btnrate.setEnabled(false);

        new LoadDetails().execute();

        btnpurchase.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new AlertDialog.Builder(DetailPage.this)
                        .setTitle("Confirmation")
                        .setMessage("Do you really want to purchase this item?")
                        .setIcon(android.R.drawable.ic_dialog_alert)
                        .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int whichButton) {
                                btnrate.setEnabled(true);
                            }})
                        .setNegativeButton(android.R.string.no, null).show();
            }
        });

    }

    public static float round(float value, int places) {
        if (places < 0) throw new IllegalArgumentException();

        long factor = (long) Math.pow(10, places);
        value = value * factor;
        long tmp = Math.round(value);
        return (float) tmp / factor;
    }

    private class LoadDetails extends AsyncTask<Void, Void, Void> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(DetailPage.this);
            pDialog.setMessage("Loading Details");
            pDialog.setCancelable(false);
            pDialog.setCanceledOnTouchOutside(false);
            pDialog.show();
        }

        @Override
        protected Void doInBackground(Void... arg0) {

            GetJSON getJSON = new GetJSON();

            String url = "https://thumbsup.ml/client.php?task=getdetail&rid="+randNo;

            String jsonStr = getJSON.getJSON(url,5000);

            data=new ArrayList<>();

            if (jsonStr != null) {
                try {

                    JSONObject jsonObj = new JSONObject(jsonStr);
                    JSONArray reviews = jsonObj.getJSONArray("reviews");

                    JSONObject datarow = new JSONObject(jsonObj.getString("details"));

                    final String itemname = datarow.getString("name");
                    final String itemprice = datarow.getString("price");
                    final String itemdesc = datarow.getString("description");

                    final String finalitemID = datarow.getString("id");
                    itemID = finalitemID;

                    final float itemtasterat = round(Float.parseFloat(jsonObj.getString("taste")),1);
                    final float itempricerat = round(Float.parseFloat(jsonObj.getString("price")),1);
                    final float itemqualirat = round(Float.parseFloat(jsonObj.getString("quality")),1);
                    final float itemfullrat = round(((itemtasterat + itempricerat + itemqualirat)/3),1);

                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {

                            Toast.makeText(DetailPage.this, itemID, Toast.LENGTH_SHORT).show();

                            pricedisp.setText(itemprice+" LKR");
                            descdisp.setText(itemdesc);
                            collapsingToolbarLayout.setTitle(itemname);

                            ratprice.setText(String.valueOf(itempricerat)+"/5");
                            ratquality.setText(String.valueOf(itemqualirat)+"/5");
                            ratteste.setText(String.valueOf(itemtasterat)+"/5");
                            ratfull.setText(String.valueOf(itemfullrat)+"/5");
                            matratbar.setRating(itemfullrat);

                            btnrate.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    Intent intent = new Intent(DetailPage.this,RateItem.class);

                                    intent.putExtra("itemID", itemID);

                                    startActivity(intent);
                                }
                            });


                        }
                    });

                    for (int i = 0; i < reviews.length(); i++) {

                        JSONObject c = reviews.getJSONObject(i);

                        String uname = c.getString("uname");
                        String quality = c.getString("quality");
                        String taste = c.getString("taste");
                        String price = c.getString("price");
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
                DetailPage.this.runOnUiThread(new Runnable() {
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
            radapter = new ReviewAdapter(DetailPage.this,data);
            rv.setAdapter(radapter);
            rv.setLayoutManager(new LinearLayoutManager((DetailPage.this)));
            pDialog.cancel();
        }
    }
}