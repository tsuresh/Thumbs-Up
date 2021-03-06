package food.suresh.com.foodbrowser;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.location.LocationManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.provider.Settings;
import android.support.annotation.Nullable;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
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

import butterknife.ButterKnife;
import butterknife.InjectView;
import butterknife.Optional;

public class Login extends AppCompatActivity {
    EditText email;
    EditText password;
    Button login;
    TextView signuplink;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        SharedPreferences sp = getSharedPreferences("Login", 0);
        String user = sp.getString("username", null);

        if (user != null && !user.isEmpty()) {
            Intent intent = new Intent(getApplicationContext(), HomeActivity.class);
            startActivity(intent);
        } else {
            setContentView(R.layout.activity_login);

            email = (EditText) findViewById(R.id.input_email);
            password = (EditText) findViewById(R.id.input_password);
            login = (Button) findViewById(R.id.btn_login);
            signuplink = (TextView) findViewById(R.id.link_signup);

            login.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    login(email.getText().toString(), password.getText().toString());
                }
            });

            signuplink.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(getApplicationContext(), Register.class);
                    startActivity(intent);

                }
            });

        }

    }

    public void login(String temail, String tpassword) {
        if (validate() == false) {
            onLoginFailed();
            return;
        }

        login.setEnabled(true);

        ProceedLogin proceedlogin = new ProceedLogin();
        proceedlogin.execute(temail, tpassword);
    }

    public boolean validate() {
        boolean valid = true;

        String email_in = email.getText().toString();
        String password_in = password.getText().toString();

        if (email_in.isEmpty() || !Patterns.EMAIL_ADDRESS.matcher(email_in).matches()) {
            email.setError("invalid email address");
            valid = false;
        } else {
            email.setError(null);
        }

        if (password_in.isEmpty() || password_in.length() < 4 || password_in.length() > 10) {
            password.setError("between 4 and 10 alphanumeric characters");
            valid = false;
        } else {
            password.setError(null);
        }

        return valid;
    }

    private void onLoginSuccess() {
        return;
    }

    public void onLoginFailed() {
        Toast.makeText(getBaseContext(), "Login failed", Toast.LENGTH_LONG).show();
        login.setEnabled(true);
    }


    class ProceedLogin extends AsyncTask<String, String, String> {

        private ProgressDialog loginprogress;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            loginprogress = new ProgressDialog(Login.this);
            loginprogress.setIndeterminate(true);
            loginprogress.setMessage("Authenticating...");
            loginprogress.show();
        }

        @Override
        protected String doInBackground(String... params) {

            String email_in = params[0];
            String password_in = params[1];

            try {
                String link = "https://thumbsup.ml/client.php?task=login&username=" + Uri.encode(email_in) + "&password=" + Uri.encode(password_in);
                URL url = new URL(link);
                HttpClient client = new DefaultHttpClient();
                HttpGet request = new HttpGet();
                request.setURI(new URI(link));
                HttpResponse response = client.execute(request);
                BufferedReader in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));

                String json = in.readLine();
                JSONObject jsonObject = new JSONObject(json);

                String message = jsonObject.getString("message");

                if(message.equals("success")){
                    String hash = jsonObject.getString("hash");
                    String fullname = jsonObject.getString("fullname");
                    String uid = jsonObject.getString("uid");

                    SharedPreferences sp = getSharedPreferences("Login", 0);
                    SharedPreferences.Editor Ed = sp.edit();

                    Ed.putString("username", email_in);
                    Ed.putString("hash", hash);
                    Ed.putString("fullname", fullname);
                    Ed.putString("uid", uid);

                    Ed.commit();
                }

                in.close();
                return message;

            } catch (Exception e) {
                return e.toString();
            }
        }

        @Override
        protected void onPostExecute(String s) {
            super.onPostExecute(s);

            loginprogress.dismiss();

            if (s.equalsIgnoreCase("success")) {

                //Open Main Activity
                Intent intent = new Intent(getApplicationContext(), HomeActivity.class);
                startActivity(intent);
                //finish();

            } else {
                Toast.makeText(Login.this, s, Toast.LENGTH_LONG).show();
            }
        }
    }

}