<div style='width: 50%'>

<p>
  <h3 class="sub-section-title">Registration type</h3>
  <select size=1 name=registration_type>
    <option <?php if ($reg->registration_type == 'professional') { echo 'selected'; } ?> value='professional'>Professional — 3750 CZK</option>
    <option <?php if ($reg->registration_type == 'hobbyist') { echo 'selected'; } ?> value='hobbyist'>Hobbyist — 500 CZK</option>
    <option <?php if ($reg->registration_type == 'student') { echo 'selected'; } ?> value='student'>Student — 250 CZK</option>
    <option <?php if ($reg->registration_type == 'code') { echo 'selected'; } ?> value='code'>I have a code</option>
  </select>
  <span id=code_span style='display: none'>
    <br>
    <input type=text name=registration_code <?php if ($reg->registration_code) { echo "value=$reg->registration_code"; } ?> />
    <?php if ($info->registration_code) { ?>
      <br>
      <span style='color: red'>*</span> This code isn't valid
    <?php } ?>
  </span>

  <div style='font-size: 80%; line-height: 130%; margin-top: 1.5em' >
  • The GUADEC registration fee is there to cover the conference
  costs. Any remaining funds, if any, are donated to the GNOME
  Foundation.<br>• By registering as Professional your contribution
  will be recognized by an invitation to a special networking
  event.<br>• We have included hobbyist and student levels to make
  GUADEC more affordable for those who need it. If even these fees are
  a hardship for you, please contact us. We don't want anyone to be
  excluded due to registration fees.</div>

</p>

<p>
  <h3 class="sub-section-title">T-Shirt</h3>
  <input type=checkbox name=tshirt <?php if ($reg->tshirt) { echo 'checked=true'; } ?>/>
  I want a GUADEC 2013 t-shirt
  <span style='float: right'>400 CZK</span>
  <span id=tshirt_span style='display: none'>
    <br>
    Type:
    <input type=radio name=tshirt_gender value='male' <?php if ($reg->tshirt_gender == 'male') { echo 'checked=true'; } ?> /> Men's
    <input type=radio name=tshirt_gender value='female' <?php if ($reg->tshirt_gender == 'female') { echo 'checked=true'; } ?> /> Women's
    <?php if ($info->tshirt_gender) { ?>
      <br>
      <span style='color: red'>*</span> Please specify a T-Shirt type
    <?php } ?>
    <br>
    Size:
    <select size=1 name=tshirt_size>
      <option <?php if ($reg->tshirt_size == 's') { echo 'selected'; } ?> value='s'>S</option>
      <option <?php if ($reg->tshirt_size == 'm') { echo 'selected'; } ?> value='m'>M</option>
      <option <?php if ($reg->tshirt_size == 'l') { echo 'selected'; } ?> value='l'>L</option>
      <option <?php if ($reg->tshirt_size == 'xl') { echo 'selected'; } ?> value='xl'>XL</option>
      <option <?php if ($reg->tshirt_size == 'xxl') { echo 'selected'; } ?> value='xxl'>XXL</option>
    </select>
    <br>
    <input type=checkbox name=foundation <?php if ($reg->foundation) { echo 'checked=true'; } ?>/>
    I am a GNOME Foundation member
    <span style='float: right'>-100 CZK</span>
  </span>
</p>

<p>
  <h3 class="sub-section-title">Meals</h3>
  <input type=checkbox name=lunch <?php if ($reg->lunch) { echo 'checked=true'; } ?>/>
  I'll have lunch at the venue in the core days (Aug. 1 to 4)
  <span style='float: right'>440 CZK</span>
  <span id=lunch_span style='display: none'>
    <br>
    <input type=checkbox name=vegetarian <?php if ($reg->vegetarian) { echo 'checked=true'; } ?>/>
    Vegetarian
  </span>
</p>

<p>
  <h3 class="sub-section-title">Accommodation</h3>
  <input type=checkbox name=dormitory <?php if ($reg->dormitory) { echo 'checked=true'; } ?>/>
  Please, book me accommodation at the <a href='https://www.guadec.org/?page_id=695#taufer'>Taufer dormitory</a>
  <span id=dormitory_span style='display: none'>

  <div style='font-size: 80%; line-height: 130%; margin: 1em 0 1em' >
  • We will book a room and optionally breakfast for you but the
  payment shall be done directly to the Taufer dormitory on
  check-in.<br>• Prices are 310 CZK or 12,5 EUR night/person in a
  shared room, 420 CZK or 17 EUR night/person in a single room and the
  optional breakfast is 100 CZK or 4 EUR day/person.<br>• Payments can
  be done via credit card or by cash in CZK or EUR.</div>

    <input type=checkbox name=breakfast <?php if ($reg->breakfast) { echo 'checked=true'; } ?>/>
    Breakfast
    <div style='display: table'>
      <div style='display: table-row'>
      <span style='display: table-cell'>Check-in date:</span>
      <input style='display: table-cell' type=text name=check_in_date id=check_in_date <?php if ($reg->check_in_date) { echo "value=$reg->check_in_date"; } ?> />
      </div>
      <div style='display: table-row'>
      <span style='display: table-cell'>Check-out date:</span>
      <input style='display: table-cell' type=text name=check_out_date id=check_out_date <?php if ($reg->check_out_date) { echo "value=$reg->check_out_date"; } ?> />
      </div>
    </div>
    <?php if ($info->check_in_out_dates) { ?>
      <span style='color: red'>*</span> These dates must delimit a non-empty interval between 2013-07-14 and 2013-08-17
      <br>
    <?php } ?>
    Gender:
    <input type=radio name=gender value='male' <?php if ($reg->gender == 'male') { echo 'checked=true'; } ?> /> Male
    <input type=radio name=gender value='female' <?php if ($reg->gender == 'female') { echo 'checked=true'; } ?> /> Female
    <?php if ($info->gender) { ?>
      <br>
      <span style='color: red'>*</span> Please specify your gender
    <?php } ?>
    <br>
    Room type:
    <input type=radio name=room value=single <?php if ($reg->room == 'single') { echo 'checked=true'; } ?> /> Single
    <input type=radio name=room value=double <?php if ($reg->room == 'double') { echo 'checked=true'; } ?> /> Double
    <?php if ($info->room) { ?>
      <br><span style='color: red'>*</span> Please specify a room type
    <?php } ?>
    <span id=double_room_span style='display: none'>
      <br>
      Preferred roommate:
      <input type=text name=roommate <?php if ($reg->roommate) { echo "value=$reg->roommate"; } ?> />

  <div style='font-size: 80%; line-height: 130%; margin: 1em 0 1em' >
  • If you enter a GUADEC participant's name we'll book a shared room
  for both of you.<br>• Leave empty if you don't mind us choosing a
  same gender participant at random who also doesn't specify a
  roommate.</div>

    </span>
  </span>
</p>

<p>
  <h3 class="sub-section-title" style='margin-bottom: 0 !important'>Notes for the Organization</h3>

  <div style='font-size: 80%; line-height: 130%; margin: 1em 0 1em' >
  • Like dietary preferences, allergies, disabilities, children…</div>

  <textarea name=notes rows=5 cols=40><?php if ($reg->notes) { echo "$reg->notes"; } ?></textarea>
</p>

</div>

<link rel="stylesheet" <?php echo "href=$jquery_url/themes/base/jquery.ui.all.css"; ?> />
<script <?php echo "src=$jquery_url/jquery-1.9.1.js"; ?> ></script>
<script <?php echo "src=$jquery_url/ui/jquery.ui.core.js"; ?> ></script>
<script <?php echo "src=$jquery_url/ui/jquery.ui.widget.js"; ?> ></script>
<script <?php echo "src=$jquery_url/ui/jquery.ui.datepicker.js"; ?> ></script>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults({ dateFormat: 'yy-mm-dd' });
  $('#check_in_date').datepicker();
  $('#check_out_date').datepicker();

  if ($('select[name=registration_type]').val() == 'code') {
    $('#code_span').show();
  }
  $('select[name=registration_type]').change(function() {
    if ($(this).val() == 'code') {
      $('#code_span').show();
    } else {
      $('#code_span').hide();
    }
  });

  if ($('input[name=tshirt]').prop('checked')) {
    $('#tshirt_span').show();
  }
  $('input[name=tshirt]').change(function() {
    if ($(this).prop('checked')) {
      $('#tshirt_span').show();
    } else {
      $('#tshirt_span').hide();
    }
  });

  if ($('input[name=lunch]').prop('checked')) {
    $('#lunch_span').show();
  }
  $('input[name=lunch]').change(function() {
    if ($(this).prop('checked')) {
      $('#lunch_span').show();
    } else {
      $('#lunch_span').hide();
    }
  });

  if ($('input[name=dormitory]').prop('checked')) {
    $('#dormitory_span').show();
  }
  $('input[name=dormitory]').change(function() {
    if ($(this).prop('checked')) {
      $('#dormitory_span').show();
    } else {
      $('#dormitory_span').hide();
    }
  });

  if ($('input[name=room]:checked').val() == 'double') {
    $('#double_room_span').show();
  }
  $('input[name=room]').change(function() {
    if ($(this).val() == 'double') {
      $('#double_room_span').show();
    } else {
      $('#double_room_span').hide();
    }
  });
});
</script>
