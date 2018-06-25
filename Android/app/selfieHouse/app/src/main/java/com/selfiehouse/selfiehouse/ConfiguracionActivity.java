package com.selfiehouse.selfiehouse;

import android.app.ListActivity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.location.Location;
import android.location.LocationManager;
import android.support.v4.app.NotificationCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.RemoteViews;
import android.widget.TextView;
import android.widget.Toast;

import com.selfiehouse.selfiehouse.Clases.ShakeListener;

public class ConfiguracionActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);      // No permite que la activity se adapte a la rotacion de pantalla
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_configuracion);


    }
}
