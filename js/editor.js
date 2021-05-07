(function() {
console.log('editor');


    tinymce.PluginManager.add('linguaviva_tc_button', function( editor, url ) {
        editor.addButton( 'text', {
            text: 'Text',
	    tooltip : 'Insert Text',
            icon: false,
            onclick: function() {
			editor.insertContent('[text]');
            }
        });


        editor.addButton( 'textarea', {
            text: 'Textarea',
	    tooltip : 'Insert Textarea',
            icon: false,
            onclick: function() {

			editor.insertContent('[textarea]');
            }
        });

        editor.addButton( 'sentence', {
            text: 'Sentence',
	    tooltip : 'Insert Sentence',
            icon: false,
            onclick: function() {

			editor.insertContent('[sentence]');
            }
        });

        editor.addButton( 'radiobuttons', {
            text: 'Radio buttons',
	    tooltip : 'Insert Radio Buttons',
            icon: false,
            onclick: function() {

			editor.insertContent("[radio]<br/>*Option 1<br/>*Option 2<br/>*Option 3<br/>[/radio]");
            }
        });

        editor.addButton( 'checkboxes', {
            text: 'Checkboxes',
	    tooltip : 'Insert Checkboxes',
            icon: false,
            onclick: function() {

			editor.insertContent("[checkboxes]<br/>*Option 1<br/>*Option 2<br/>*Option 3<br/>[/checkboxes]");
            }
        });

        editor.addButton( 'dropdown', {
            text: 'Dropdown',
	    tooltip : 'Insert Dropdown menu',
            icon: false,
            onclick: function() {

			editor.insertContent("[dropdown choices='Option 1,Option 2,Option 3']");
            }
        });




        editor.addButton( 'googleimages', {
            text: 'GoogleImages',
	    tooltip : 'Insert Link to Google Images',
            icon: false,
            onclick: function() {
			selection = tinyMCE.activeEditor.selection.getContent();
			editor.insertContent("<a href='https://www.google.com/search?site=&tbm=isch&q="+selection+"' target='_blank'>"+selection+"</a>");
            }
        });

        editor.addButton( 'priberam', {
            text: 'Dicionário Priberam',
	    tooltip : 'Insert Link to Dicionário Priberam',
            icon: false,
            onclick: function() {
			selection = tinyMCE.activeEditor.selection.getContent();
			editor.insertContent("<a href='https://dicionario.priberam.org/"+selection+"' target='_blank'>"+selection+"</a>");
            }
        });


    });



})();
