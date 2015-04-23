define( [ 'initializer', 'configuration/breadcrumbTitleConfiguration' ], function(contaazulApp, breadcrumbTitles) {

	contaazulApp.directive( "breadcrumb", [ '$location', function($location) {
		return {
			restrict : 'A',
			link : function(scope, $element) {
				var url = "";
				function splitParts(location) {
					var parts = location.substring( 1 ).split( "/" );
					if (!isNaN( parts.slice( -1 )[0] ))
						parts.pop();
					return parts;
				}

				function updateBreadcrumbs(location) {
					if (location === '/visao-geral' || location === '/bem-vindo')
						hideBreadcrumbs();
					else
						buildBreadcrumbs( location );
				}

				function hideBreadcrumbs() {
					$element.hide();
				}

				function buildBreadcrumbs(location) {
					$element.show();
					url = "";
					var urlParts = splitParts( location );
					scope.breadcrumbs = [];
					for ( var i = 0, length = urlParts.length; i < length; i++)
						addCrumb( urlParts[i], i !== (length - 1) );
				}

				function addCrumb(crumb, clickable) {
					url += '/' + crumb;

					var target = "javascript:void(0);";
					if (clickable)
						target = "javascript:contaazul.util.loadItem('#" + url + "')";

					scope.breadcrumbs.push( {
						href : target,
						title : getTitle( crumb )
					} );
				}

				function getTitle(crumb) {
					if (breadcrumbTitles[crumb])
						return breadcrumbTitles[crumb];
					return convertToTitle( crumb );
				}

				function convertToTitle(crumb) {
					return capitalise( crumb.split( "-" ).join( " " ) );
				}

				function capitalise(string) {
					return string.charAt( 0 ).toUpperCase() + string.slice( 1 ).toLowerCase();
				}

				scope.$watch( function() {
					return $location.path();
				}, function() {
					updateBreadcrumbs( $location.path() );
				}, true );

				function changeBreadcrumbAccordingToLocation(newLocation) {
					contaazul.events.listen( "locationChanged", changeBreadcrumbAccordingToLocation );
					updateBreadcrumbs( newLocation );
					scope.$apply();
				}

				function setupLocationListener() {
					contaazul.events.listen( "locationChanged", changeBreadcrumbAccordingToLocation );
				}

				updateBreadcrumbs( $location.path() );
				setupLocationListener();
			}
		};
	} ] );
} );
