package food.suresh.com.foodbrowser;


import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import food.suresh.com.foodbrowser.reviews.ReviewData;


/**
 * A simple {@link Fragment} subclass.
 */
public class ShopsFragment extends Fragment {

    private ProgressDialog pDialog;
    private RecyclerView rv;
    private ReviewAdapter radapter;
    public List<ReviewData> data;

    public ShopsFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.fragment_shops, container, false);
        rv = (RecyclerView)v.findViewById(R.id.storelist);

        new LoadDetails().execute();

        return v;
    }

    private class LoadDetails extends AsyncTask<Void, Void, Void> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(getActivity());
            pDialog.setMessage("Loading Details");
            pDialog.setCancelable(false);
            pDialog.setCanceledOnTouchOutside(false);
            pDialog.show();
        }

        @Override
        protected Void doInBackground(Void... arg0) {

            GetJSON getJSON = new GetJSON();

            String url = "https://thumbsup.ml/client.php?task=storelist";

            String jsonStr = getJSON.getJSON(url,5000);

            data=new ArrayList<>();

            if (jsonStr != null) {
                try {

                    JSONObject jsonObj = new JSONObject(jsonStr);
                    JSONArray reviews = jsonObj.getJSONArray("stores");

                    for (int i = 0; i < reviews.length(); i++) {

                        JSONObject c = reviews.getJSONObject(i);

                        String uname = c.getString("storename");
                        String quality = "4.0";
                        String taste = "3.0";
                        String price = "2.0";
                        String description = c.getString("location");

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
                    getActivity().runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            Toast.makeText(getContext(), "Json parsing error: " + e.getMessage(),Toast.LENGTH_LONG).show();
                        }
                    });

                }

            } else {

                Log.e("Error","Couldn't get json from server.");
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        Toast.makeText(getContext(), "Couldn't get json from server. Check LogCat for possible errors!", Toast.LENGTH_LONG).show();
                    }
                });

            }

            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            radapter = new ReviewAdapter(getActivity(),data);
            rv.setAdapter(radapter);
            rv.setLayoutManager(new LinearLayoutManager((getActivity())));
            pDialog.cancel();
        }
    }

}
