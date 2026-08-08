 <!-- Archive Button -->
 <button type="button" class="btn btn-danger" title="Archive" data-bs-toggle="modal"
     data-bs-target="#archiveModal{{ $order->id }}">
     <i class="fas fa-archive"></i>
 </button>

 <!-- Archive Confirmation Modal -->
 <div class="modal fade" id="archiveModal{{ $order->id }}" tabindex="-1" aria-labelledby="archiveModalLabel"
     aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 Are you sure you want to archive <b>{{ $order->name }}
                     {{ $order->last_name }}'s order</b>? <br> <b class="text-danger">You cannot undo this action!</b>
             </div>
             <div class="modal-footer">
                 <form action="{{ route('admin.archieve.order', $order->id) }}" method="POST" class="d-inline">
                     @csrf
                     @method('PUT')
                     <button type="submit" class="btn btn-danger">Archive Order</button>
                 </form>
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
             </div>
         </div>
     </div>
 </div>
