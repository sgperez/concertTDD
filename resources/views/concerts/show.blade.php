<h1>{{ $concert->title }}</h1>
<div>{{ $concert->subtitle }} </div>
<div>{{ $concert->formatted_date }} </div>
<div>Doors at: {{ $concert->formatted_start_time }} </div>
<div>Price : ${{ $concert->ticket_price_in_dollars }} </div>
<div>{{ $concert->venue }} </div>
<div>{{ $concert->venue_address }} </div>
<div>{{ $concert->city }} </div>
<div>{{ $concert->state }} </div>
<div>{{ $concert->postcode }} </div>
<div>{{ $concert->additional_info }} </div>
