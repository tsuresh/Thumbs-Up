package food.suresh.com.foodbrowser;


import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import static android.app.Activity.RESULT_OK;


/**
 * A simple {@link Fragment} subclass.
 */
public class HomeFragment extends Fragment {

    FloatingActionButton scanbtn;
    private static final int QR_RESULT_CODE = 0;

    public HomeFragment() {

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.fragment_home, container, false);

        scanbtn = (FloatingActionButton) v.findViewById(R.id.scanbtn);

        scanbtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent myIntent = new Intent(getActivity(), DecoderActivity.class);
                startActivityForResult(myIntent, QR_RESULT_CODE);
            }
        });

        return v;
    }


    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == QR_RESULT_CODE) {
            if (resultCode == RESULT_OK) {
                // get String data from Intent
                String returnString = data.getStringExtra("qrCode");
                Intent detailintent = new Intent(getActivity(),DetailPage.class);
                detailintent.putExtra("qrCode",returnString);
                startActivity(detailintent);
            }
        }
    }
}
