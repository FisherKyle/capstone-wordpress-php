CKEDITOR.dialog.add( 'acprDialog', function( editor ) {
    return {
        title: 'cpeviation Properties',
        minWidth: 400,
        minHeight: 200,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'cp',
                        label: 'cpeviation',
                        validate: CKEDITOR.dialog.validate.notEmpty( "cpeviation field cannot be empty." )
                    },
                    {
                        type: 'text',
                        id: 'title',
                        label: 'Explanation',
                        validate: CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty." )
                    }
                ]
            },
            {
                id: 'tab-adv',
                label: 'Advanced Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'id',
                        label: 'Id'
                    }
                ]
            }
        ],
        onOk: function() {
            var dialog = this;

            var cp = editor.document.createElement( 'cp' );
            cp.setAttribute( 'title', dialog.getValueOf( 'tab-basic', 'title' ) );
            cp.setText( dialog.getValueOf( 'tab-basic', 'cp' ) );

            var id = dialog.getValueOf( 'tab-adv', 'id' );
            if ( id )
                cp.setAttribute( 'id', id );

            editor.insertElement( cp );
        }
    };
});