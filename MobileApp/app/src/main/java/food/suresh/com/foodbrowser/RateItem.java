package food.suresh.com.foodbrowser;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.AsyncTask;
import android.provider.Telephony;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URI;
import java.net.URL;

import me.zhanghai.android.materialratingbar.MaterialRatingBar;

public class RateItem extends AppCompatActivity {

    MaterialRatingBar taste;
    MaterialRatingBar quality;
    MaterialRatingBar price;
    EditText desc;
    Button submit;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_rate_item);

        taste = (MaterialRatingBar) findViewById(R.id.ratetaste);
        quality = (MaterialRatingBar) findViewById(R.id.ratequality);
        price = (MaterialRatingBar) findViewById(R.id.rateprice);
        desc = (EditText) findViewById(R.id.ratedescription);
        submit = (Button) findViewById(R.id.submitrating);

        final String itemID = getIntent().getStringExtra("itemID");

        SharedPreferences sp = getSharedPreferences("Login", 0);
        final String user = sp.getString("fullname", null);
        final String uid = sp.getString("uid", null);

        submit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                String finaltaste = String.valueOf(taste.getRating());
                String finalquality = String.valueOf(quality.getRating());
                String finalprice = String.valueOf(price.getRating());
                String finaldesc = desc.getText().toString();

                RatetheItem rate = new RatetheItem();
                rate.execute(user,uid,itemID,finalprice,finalquality,finaltaste,finaldesc);

            }
        });
    }

    class RatetheItem extends AsyncTask<String, String, String> {

        private ProgressDialog regprogress;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            regprogress = new ProgressDialog(RateItem.this);
            regprogress.setIndeterminate(true);
            regprogress.setMessage("Processing...");
            regprogress.show();
        }

        @Override
        protected String doInBackground(String... params) {

            String name = params[0];
            String uid = params[1];
            String itemid =  params[2];

            String price =  params[3];
            String quality =  params[4];
            String taste =  params[5];
            String description =  params[6];

            try{
                String link = "https://thumbsup.ml/client.php?task=addrating&name="+ Uri.encode(name)+"&uid="+Uri.encode(uid)+"&itemid="+Uri.encode(itemid)+"&price="+Uri.encode(price)+"&quality="+Uri.encode(quality)+"&taste="+Uri.encode(taste)+"&description="+Uri.encode(description);
                URL url = new URL(link);
                HttpClient client = new DefaultHttpClient();
                HttpGet request = new HttpGet();
                request.setURI(new URI(link));
                HttpResponse response = client.execute(request);
                BufferedReader in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));

                String json = in.readLine();
                JSONObject jsonObject = new JSONObject(json);
                String message = jsonObject.getString("message");

                in.close();
                return message;
            }

            catch(Exception e){
                return e.toString();
            }
        }

        @Override
        protected void onPostExecute(String s) {
            super.onPostExecute(s);

            regprogress.dismiss();

            if(s.equalsIgnoreCase("success")){

                Toast.makeText(getBaseContext(), "Your rating has been successfully sent. Please check your profile to check your points.", Toast.LENGTH_LONG).show();

                Intent intent = new Intent(getApplicationContext(), Login.class);
                startActivity(intent);

            } else {
                Toast.makeText(RateItem.this, s, Toast.LENGTH_LONG).show();
            }
        }


    }
}
