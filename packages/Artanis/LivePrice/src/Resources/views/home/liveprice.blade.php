<section class="featured-products">

    <div class="featured-heading">
        Live Price<br/>

        <span class="featured-seperator" style="color:lightgrey;">_____</span>
    </div>

    <div class="product-grid-4">
          <div class="title-table2 col-xs-12" id="red-table2">
            <b>Pokli Gold Price  (24 Hours Live)<br><font size="2">(Last updated 05-Dec-2019 11:36:01)</font></b>
          </div>
          <b>
          		<div class="all-live-price-div2 col-lg-12" id="orange-table2">
          			<div class="col-md-12 col-xs-12">
          			<div id="section-1" class="col-md-3 col-sm-6 col-xs-12" style="display: block;height: 533px;">
                  <div class="live-price-div2" id="gap-div-table2">
            				<div class="gold-live-price-title-table2" id="gap-div-title2">
            					<a href="{{ route('gapsap.index') }}" target="_BLANK"><img src="/images/liveprice/gap.png" alt="Gold Program" width="245px" height="15px" style="position: relative; top:-4px;"></a>
            				</div>
            					<table class="gold-live-price-table2" id="gap-table-content2">
                        @foreach ($gap as $gaps)
                        <tr>
                        <td>{{ $gaps->last_updated }}</td>
                        <td>{{ $gaps->gram }}</td>
                        <td>{{ $gaps->price }}</td>
                        </tr>
                        @endforeach
                    </table>
                       <a href="{{ route('gapsap.index') }}"><img style="display:block; width:230px; margin: 10px;" src="/images/liveprice/BuyGAP.png"></a>
    			        </div>
           <!-- BungaMas Taifook -->
          	<!-- End BungaMas TaiFook -->
          			<div class="live-price-bottom-label2" id="live-price-bottom-label2">
                      <span style="font-style:italic;line-height:1; "><br><br>
          			* All LBMA products are SST exempted. <br>* All prices are quoted in Malaysia Ringgit (MYR) and excluding Gold Premium
          			</span>
          			</div>
          		</div>
          </b>
        </div>
      </div>
    </div>

</section>