package com.selfiehouse.selfiehouse.Clases;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageButton;
import android.widget.TextView;

import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.Notificacion;
import com.selfiehouse.selfiehouse.R;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

public class NotificacionAdapter extends BaseAdapter {
    private List<Notificacion> listNotificacion;
    private Context contexto;
    private int layout;
    private String[] listComentario;
    private String notificacion = "";
    private String comentario = "";

    public NotificacionAdapter(Context contexto, int layout, List<Notificacion> notificaciones){
        this.contexto = contexto;
        this.layout = layout;
        this.listNotificacion = notificaciones;

    }
    @Override
    public int getCount() {
        return this.listNotificacion.size();
    }

    @Override
    public Object getItem(int position) {
        return this.listNotificacion.get(position);
    }

    @Override
    public long getItemId(int id) {
        return id;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        //Copio la vista
        View v = convertView;
        SimpleDateFormat formateador = new SimpleDateFormat("dd/MM/yy");

        //Inflo la vista que me llega con el layout personalizado
        LayoutInflater layoutInflater = LayoutInflater.from(this.contexto);
        v = layoutInflater.inflate(R.layout.item_notificacion,null);

        //Me traigo el valor actual dependiendo de la posicion
        Notificacion list = listNotificacion.get(position);

        //Referencio el elemento a modificar y lo lleno
        TextView tvNotificacion = (TextView) v.findViewById(R.id.tvNotificacion);
        TextView tvComentario = (TextView) v.findViewById(R.id.tvComentario);
        TextView tvFecha = (TextView) v.findViewById(R.id.tvFecha);

        //listComentario = list.getComentario().split(".");


        listComentario = list.getComentario().split("\\.");
        for (String item : listComentario)
        {
            if(notificacion == ""){
                notificacion = item;
            }
            else {
                comentario = item;
            }
        }

        //tvNotificacion.setText(list.getComentario());
        tvNotificacion.setText(notificacion);
        tvComentario.setText(comentario);
        notificacion = "";
        comentario = "";
        tvFecha.setText(list.getFecha());

        //Devuelvo la vista inflada y modificada con nuestros datos
        return v;
    }

}
