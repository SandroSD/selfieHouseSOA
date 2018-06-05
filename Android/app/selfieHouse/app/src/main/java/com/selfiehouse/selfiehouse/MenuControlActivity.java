package com.selfiehouse.selfiehouse;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

public class MenuControlActivity extends AppCompatActivity {

    private TextView mTextMessage;
    Switch switchSistema, switchDEBUG, switchBuzzer, switchVentilador, switchTraba, switchLEDVerde, switchLEDRojo;

    private BottomNavigationView.OnNavigationItemSelectedListener mOnNavigationItemSelectedListener
            = new BottomNavigationView.OnNavigationItemSelectedListener() {

        @Override
        public boolean onNavigationItemSelected(@NonNull MenuItem item) {
            switch (item.getItemId()) {
                case R.id.navigation_home:
                    mTextMessage.setText(R.string.title_home);
                    return true;
                case R.id.navigation_dashboard:
                    mTextMessage.setText(R.string.title_dashboard);
                    return true;
                case R.id.navigation_notifications:
                    mTextMessage.setText(R.string.title_notifications);
                    return true;
            }
            return false;
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_control);

        /* Cambio los titulos para que no sean editables*/
        EditText mEdit = (EditText) findViewById(R.id.titleEstadoActuadores);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoAlertas);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoSistema);
        mEdit.setEnabled(false);

        mTextMessage = (TextView) findViewById(R.id.message);
        BottomNavigationView navigation = (BottomNavigationView) findViewById(R.id.navigation);
        navigation.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener);

        /* Listener para switchSistema */
        switchSistema = (Switch) findViewById(R.id.switchSistema);
        switchSistema.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"selfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"selfieHouse: DESACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchSistema.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }


        /* Listener para switchDEBUG */
        switchDEBUG = (Switch) findViewById(R.id.switchDebug);
        switchDEBUG.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"Modo Debug: ACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"Modo Debug: DESACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchDEBUG.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }

        /* Listener para switchVentilador */
        switchVentilador = (Switch) findViewById(R.id.switchVentilador);
        switchVentilador.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"Ventilador: DESACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchVentilador.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }

        /* Listener para switchBuzzer */
        switchBuzzer = (Switch) findViewById(R.id.switchBuzzer);
        switchBuzzer.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"Buzzer: ACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"Buzzer: DESACTIVADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchBuzzer.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }

        /* Listener para switchTraba */
        switchTraba = (Switch) findViewById(R.id.switchTraba);
        switchTraba.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"Puerta trabada", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"Puerta destrabada", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchTraba.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }

        /* Listener para switchLEDRojo */
        switchLEDRojo = (Switch) findViewById(R.id.switchLEDRojo);
        switchLEDRojo.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"LED Rojo: ENCENDIDO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"LED Rojo: APAGADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchLEDRojo.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }

        /* Listener para switchLEDVerde */
        switchLEDVerde = (Switch) findViewById(R.id.switchLEDVerde);
        switchLEDVerde.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast
                    Toast.makeText(MenuControlActivity.this,"LED Verde: ENCENDIDO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOn);
                } else {
                    Toast.makeText(MenuControlActivity.this,"LED Verde: APAGADO", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });

        if (switchLEDVerde.isChecked()) {
            //textView.setText(switchOn);
        } else {
            //switchSistema.setText(switchOff);
        }
    }

}
