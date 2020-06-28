@if ($showFilter ?? '')

<div class="modal fade" id="locationModel" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="locationModel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="locationModel">Filter By Location</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <p class="text-muted">Enter The location where Your trying to find book 👇 </p>
       <input class="form-control mb-2" type="text" value="{{ $location ?? '' }}" name="location" placeholder="Station, college, landmark and etc">
       <p class="text-muted">🤠 use comman ( , ) to enter more then one location</p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary rounded-0 apply-filter" data-dismiss="modal">Apply</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="shortModel" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="shortModel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="shortModel">Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary rounded-0">Apply</button>
      </div>
    </div>
  </div>
</div>

@endif