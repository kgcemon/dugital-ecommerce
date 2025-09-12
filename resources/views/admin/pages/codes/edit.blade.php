@extends('admin.layouts.app')

@section('content')
        <div class="card shadow-sm border-0">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Denom</th>
                        <th>Order Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($codes as $code)
                        <tr>
                            <td>{{ $code->id }}</td>
                            <td>{{ $code->code }}</td>
                            <td>{{ $code->denom ?? '-' }}</td>
                            <td>{{ $code->order_id ?? '-' }}</td>
                            <td>{{ $code->status }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editVariantModal"
                                    data-id="{{ $code->id }}"
                                    data-code="{{ $code->code }}"
                                    data-denom="{{ $code->denom }}"
                                    data-status="{{ $code->status }}"
                                    data-item_id="{{ $code->item_id }}"
                                >Edit</button>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteVariantModal"
                                    data-id="{{ $code->id }}"
                                    data-name="{{ $code->code }}"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No variants found for this product.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $codes->links('admin.layouts.partials.__pagination') }}
                </div>
            </div>
        </div>


 <!-- Edit Code Modal -->
{{--     <div class="modal fade" id="editVariantModal" tabindex="-1" aria-labelledby="editVariantModalLabel" aria-hidden="true">--}}
{{--         <div class="modal-dialog">--}}
{{--             <form id="editVariantForm" method="POST">--}}
{{--                 @csrf--}}
{{--                 @method('PUT')--}}
{{--                 <div class="modal-content">--}}
{{--                     <div class="modal-header">--}}
{{--                         <h5 class="modal-title" id="editVariantModalLabel">Edit Code</h5>--}}
{{--                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                     </div>--}}

{{--                     <div class="modal-body">--}}
{{--                         <!-- Variant Selector -->--}}
{{--                         <div class="mb-3">--}}
{{--                             <label for="editVariantItem" class="form-label">Select Item</label>--}}
{{--                             <select class="form-select" id="editVariantItem" name="item_id" required>--}}
{{--                                 @foreach($product->items as $item)--}}
{{--                                     <option value="{{ $item->id }}">{{ $item->name }}</option>--}}
{{--                                 @endforeach--}}
{{--                             </select>--}}
{{--                         </div>--}}

{{--                         <!-- Code Input -->--}}
{{--                         <div class="mb-3">--}}
{{--                             <label for="editCodeText" class="form-label">Code</label>--}}
{{--                             <input type="text" class="form-control" id="editCodeText" name="code" required>--}}
{{--                         </div>--}}
{{--                         <div class="mb-3">--}}
{{--                             <label for="editCodeText" class="form-label">Denom</label>--}}
{{--                             <input type="text" class="form-control" id="editCodeDenom" name="denom" required>--}}
{{--                         </div>--}}
{{--                         <div class="mb-3">--}}
{{--                             <label for="editCodestatus" class="form-label">Status</label>--}}
{{--                             <select class="form-select" id="editCodestatus" name="status" required>--}}
{{--                                 <option value="used">Used</option>--}}
{{--                                 <option value="unused">Unused</option>--}}
{{--                             </select>--}}
{{--                         </div>--}}

{{--                     </div>--}}
{{--                     <div class="modal-footer">--}}
{{--                         <input type="hidden" name="code_id" value="{{ $code->id }}">--}}
{{--                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>--}}
{{--                         <button type="submit" class="btn btn-primary">Update Code</button>--}}
{{--                     </div>--}}
{{--                 </div>--}}
{{--             </form>--}}
{{--         </div>--}}
{{--     </div>--}}

     <!-- Delete Variant Modal -->
     <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteVariantModalLabel"
          aria-hidden="true">
         <div class="modal-dialog">
              The form action will be set dynamically by JavaScript
             <form id="deleteVariantForm" method="POST" action="{{ route('admin.codes.destroy', $code->id ?? 55554) }}">
                 @csrf
                 @method('DELETE')
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="deleteVariantModalLabel">Delete Variant</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <p>Are you sure you want to delete the Code "<strong id="deleteVariantName"></strong>"?</p>
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                         <button type="submit" class="btn btn-danger">Yes, Delete</button>
                     </div>
                 </div>
             </form>
         </div>
     </div>

@endsection

 @push('scripts')

     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

     <script>
         document.addEventListener('DOMContentLoaded', function () {

             // --- EDIT MODAL SCRIPT ---
             const editModal = document.getElementById('editVariantModal');
             editModal.addEventListener('show.bs.modal', function (event) {
                 const button = event.relatedTarget;

                 const id = button.getAttribute('data-id');
                 const code = button.getAttribute('data-code');
                 const eDenom = button.getAttribute('data-denom');
                 const estatus = button.getAttribute('data-status');
                 const itemId = button.getAttribute('data-item_id');


                 const form = document.getElementById('editVariantForm');
                 const codeInput = document.getElementById('editCodeText');
                 const itemSelect = document.getElementById('editVariantItem');
                 const denom = document.getElementById('editCodeDenom');
                 const status = document.getElementById('editCodestatus');

                 form.action = `/admin/codes/${id}`; // Route must match your update route
                 codeInput.value = code;
                 denom.value = eDenom;
                 status.value = estatus;

                 // Set selected item
                 [...itemSelect.options].forEach(option => {
                     option.selected = option.value === itemId;
                 });
             });

             // --- DELETE MODAL SCRIPT ---
             const deleteModal = document.getElementById('deleteVariantModal');
             deleteModal.addEventListener('show.bs.modal', function (event) {
                 const button = event.relatedTarget;
                 const id = button.getAttribute('data-id');
                 const name = button.getAttribute('data-name');

                 const form = document.getElementById('deleteVariantForm');
                 const namePlaceholder = document.getElementById('deleteVariantName');

                 form.action = `/admin/codes/${id}`;
                 namePlaceholder.textContent = name;
             });
         });
     </script>
 @endpush
