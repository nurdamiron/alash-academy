<div class="row mb-3">
	<label class="form-label ol-form-label col-sm-2 col-form-label">{{get_phrase('Pricing type')}}<span class="text-danger ms-1">*</span></label>
	<div class="col-sm-10">
		<div class="eRadios">
			<div class="form-check">
				<input type="radio" name="is_paid" value="1" class="form-check-input eRadioSuccess" id="paid" onchange="$('#paid-section').slideDown(200)" @if($bootcamp_details->is_paid == 1) checked @endif>
				<label for="paid" class="form-check-label">{{get_phrase('Paid')}}</label>
			</div>

			<div class="form-check">
				<input type="radio" name="is_paid" value="0" class="form-check-input eRadioSuccess" id="free" onchange="$('#paid-section').slideUp(200)" @if($bootcamp_details->is_paid != 1) checked @endif>
				<label for="free" class="form-check-label">{{get_phrase('Free')}}</label>
			</div>
		</div>
	</div>
</div>

<div class="paid-section @if($bootcamp_details->is_paid != 1) d-hidden @endif" id="paid-section">
	<div class="row mb-3">
		<label for="price" class="form-label ol-form-label col-sm-2 col-form-label">{{get_phrase('Price')}} <small>({{currency()}})</small><span class="text-danger ms-1">*</span></label>
		<div class="col-sm-10">
			<input type="number" name="price" value="{{$bootcamp_details->price}}" class="form-control ol-form-control" id="price" min="1" step=".01" placeholder="{{get_phrase('Enter your bootcamp price')}} ({{currency()}})">
		</div>
	</div>

	<div class="row mb-3">
		<label class="form-label ol-form-label col-sm-2 col-form-label">{{get_phrase('Discount type')}}</label>
		<div class="col-sm-10">
			<div class="eRadios">
				<div class="form-check">
					<input type="checkbox" name="discount_flag" value="1" class="form-check-input eRadioSuccess" id="discount_flag" @if($bootcamp_details->discount_flag == 1) checked @endif>
					<label for="discount_flag" class="form-check-label">{{get_phrase('Check if this bootcamp has discount')}}</label>
				</div>
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<label for="discounted_price" class="form-label ol-form-label col-sm-2 col-form-label">{{get_phrase('Discounted price')}}</label>
		<div class="col-sm-10">
			<input type="number" name="discounted_price" value="{{$bootcamp_details->discounted_price}}" class="form-control ol-form-control" id="discounted_price" min="1" step=".01"  placeholder="{{get_phrase('Enter your discount price')}} ({{currency()}})">
		</div>
	</div>
</div>
