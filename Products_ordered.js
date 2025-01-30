/**************************************
    VUE INSTANCE
**************************************/
new Vue({
    el: '#app',
    data() {
      return {
        appTitle: 'Products Cart',
        products: [
          { id: 1, title: 'Energy Absorbers', price: 9.99, image: 'energy_absorption.jpg' },
          { id: 2, title: 'Zone Equipment', price: 12.99, image: 'work_zone_equipment.jpg' },
          { id: 3, title: 'Delineation', price: 8.00, image: 'traffic_delineation.jpg' },
          { id: 4, title: 'Signs & Hardware', price: 10.50, image: 'signs_hardware.jpg' }
        ],
        cart: [],
        total: 0
      };
    },
    methods: {
      addItem(prod) {
        // Increment total price
        this.total += prod.price;
  
        let inCart = false;
        // Update quantity if the item is already in the cart
        for (let i = 0; i < this.cart.length; i++) {
          if (this.cart[i].id === prod.id) {
            inCart = true;
            this.cart[i].quantity++;
            break;
          }
        }
        // Add item if not already in the cart
        if (!inCart) {
          this.cart.push({
            id: prod.id,
            title: prod.title,
            price: prod.price,
            quantity: 1
          });
        }
      },
      add(item) {
        this.total += item.price;
        item.quantity++;
      },
      sub(item) {
        this.total -= item.price;
        if (item.quantity > 1) {
          item.quantity--;
        } else {
          for (let i = 0; i < this.cart.length; i++) {
            if (this.cart[i].id === item.id) {
              this.cart.splice(i, 1);
              break;
            }
          }
        }
      }
    },
    filters: {
      currency(price) {
        return '₹' + (price * 73.52).toFixed(2); // Assuming 1 USD = 73.52 INR
      }
    }
  });
  