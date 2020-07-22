<template>
    <!--begin::Modal-->
    <div class="modal fade" :id="id" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="modal-body"></slot>    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air" data-dismiss="modal">Fermer</button>
                    <slot name="modal-footer"></slot>                    
                </div>
            </div>
        </div>
    </div>

    <!--end::Modal-->
</template>

<script>
export default {
    name: 'basic-modal',
    props: ['id', 'title']
}
</script>
