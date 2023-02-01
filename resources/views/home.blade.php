<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coalition Test</title>
    <link rel="stylesheet" href="/vendors/bootstrap/css/bootstrap.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex" x-data="products">
    <div class="row align-self-center w-100 p-0 d-flex justify-content-center">
        <div class="col-4 bg-white p-3">
            <form class="product-form"
                  @submit.prevent="saveProduct">
                <div class="mb-3">
                    <label for="name" class="form-label">Name of product:</label>
                    <input type="text" name="name" class="form-control" id="name" x-model="productFormData.name">
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="text" name="quantity" class="form-control" id="quantity" x-model.number="productFormData.quantity">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price per item:</label>
                    <input type="text" name="price" class="form-control" x-model.number="productFormData.price" id="price">
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
        <div class="col">
            <div class="bg-white p-4">
                <h5 class="text-center mb-4">Products List</h5>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantity in stock</th>
                        <th scope="col">Price per item</th>
                        <th scope="col">Date submitted</th>
                        <th scope="col">Total value number</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <template x-for="(product, index) in products">
                        <tr class="" style="cursor: pointer">
                            <th scope="row" x-text="index + 1"></th>
                            <td x-text="product.name"></td>
                            <td x-text="product.quantity"></td>
                            <td x-text="product.price.toLocaleString()"></td>
                            <td>
                                <span x-text="product.submitted_date_formatted"></span>
                            </td>
                            <td x-text="product.total_value_number.toLocaleString()"></td>
                            <td>
                                <button @click="editProduct(product)" class="btn btn-sm btn-secondary">Edit</button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <tbody class="table-group-divider">
                    <tr>
                        <td colspan="6" class="text-end">Overall total value numbers:</td>
                        <td class="fw-bold"
                            x-text="products.reduce((totalValueNumbers, product) => product.total_value_number + totalValueNumbers, 0).toLocaleString()"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('products', () => ({
            products: [],
            totalValueNumbers: 0,
            productFormData: {
                name: '',
                quantity: 1,
                price: null,
            },
            async saveProduct(){
                const {data} = await window.axios.post('/api/products', this.productFormData);

                this.products = data;
                this.reset();
            },
            reset(){
                this.productFormData = {
                    name: '',
                    quantity: 1,
                    price: null,
                };
            },
            async loadProducts(){
                this.products = (await window.axios.get('api/products')).data;
            },
            editProduct(product){
                this.productFormData = {...product};
            },
            async init(){
                await this.loadProducts();
            }
        }))
    });

</script>
</body>
</html>
