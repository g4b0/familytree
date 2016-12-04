$(document).ready(function () {

    $.when(
            $.getJSON('/api/tree/3')
            ).then(function (r1) {
        // Success

        var cy = cytoscape({

            container: document.getElementById('cy'), // container to render in
            elements: r1,

            //boxSelectionEnabled: false,
            //autounselectify: true,

            layout: {
                name: 'dagre'
                        //name: 'breadthfirst'
            },

            style: [
                {
                    selector: 'node',
                    style: {
                        'content': 'data(id)',
                        'text-opacity': 0.5,
                        'text-valign': 'center',
                        'text-halign': 'right',
                        'background-color': '#11479e',
                        'label': 'data(name)'
                    }
                },
                {
                    selector: 'node[gender = "Female"]',
                    style: {
                        'background-color': 'red',
                    }
                },
                {
                    selector: 'edge',
                    style: {
                        'width': 4,
                        'target-arrow-shape': 'triangle',
                        'line-color': '#9dbaea',
                        'target-arrow-color': '#9dbaea',
                        'curve-style': 'bezier'
                    }
                }
            ],

        });
        
        cy.resize();
        
        var j = cy.$('#3');
        console.log(j);

        cy.nodes().on("click", function () {
            console.log(this);
        });

    }, function (deferredObject, status, textStatus) {
        // Failure
        console.log('fail');
        console.log(textStatus);
    }).always(function () {
        // Always
        console.log('always');
    });



});


