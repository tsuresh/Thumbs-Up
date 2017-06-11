package food.suresh.com.foodbrowser;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.Collections;
import java.util.List;

import food.suresh.com.foodbrowser.reviews.OfferData;


/**
 * Created by Suresh on 6/8/2017.
 */

public class OfferAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder>  {

    private Context context;
    private LayoutInflater inflater;
    List<OfferData> data= Collections.emptyList();
    OfferData current;
    int currentPos=0;

    public OfferAdapter(Context context, List<OfferData> data){
        this.context=context;
        inflater= LayoutInflater.from(context);
        this.data=data;
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view=inflater.inflate(R.layout.offer_item, parent, false);
        MyHolder holder=new MyHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        MyHolder myHolder= (MyHolder) holder;
        current=data.get(position);

        myHolder.offername.setText(current.offername);
        myHolder.offerdesc.setText(current.description);
        myHolder.reqpoints.setText(current.points+" points required");
    }

    @Override
    public int getItemCount() {
        return data.size();
    }

    class MyHolder extends RecyclerView.ViewHolder{

        TextView offername;
        TextView offerdesc;
        TextView reqpoints;

        // create constructor to get widget reference
        public MyHolder(View itemView) {
            super(itemView);
            offername= (TextView) itemView.findViewById(R.id.offername);
            offerdesc= (TextView) itemView.findViewById(R.id.offdesc);
            reqpoints = (TextView) itemView.findViewById(R.id.reqpoints);
        }

    }
}
