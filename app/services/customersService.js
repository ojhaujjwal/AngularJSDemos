//This handles retrieving data and is used by controllers. 3 options (server, factory, provider) with 
//each doing the same thing just structuring the functions/data differently.
app.service('customersService', function ($http) {
    var customers = [];

    this.getCustomers = function () {

        return $http({ method: 'GET', url: 'api/customer' }).then(function (response) {
            customers = response.data;
            return customers;
        }, function (data, status, headers, config) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
        });
    };

    this.getCustomers();

    this.insertCustomer = function (firstName, lastName, city) {
        data = {
            firstName: firstName,
            lastName: lastName,
            city: city
        };
        console.log(data);

        return $http({ method: 'POST', url: 'api/customer', data: data}).then(function(response) {
            return response.data;
        });
        //return $http.post('api/customer', data);
    };

    this.deleteCustomer = function (id) {
        return $http.delete('api/customer/' + id);
    };

    this.getCustomer = function (id) {

        return $http({ method: 'GET', url: 'api/customer/' + id }).then(function (response) {
            return response.data;
            return customers;
        }, function (data, status, headers, config) {

        })
    };

});