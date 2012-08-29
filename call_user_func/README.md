```shell
$ php -v
PHP 5.3.15-pmsipilot009 with Suhosin-Patch (cli) (built: Jul 27 2012 11:56:28)

$ php bench.php 10000
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                                   X VS Y                                  |  X_ = AVG(X) |  Y_ = AVG(Y) | (1 - (Y_ / X_)) * 100 |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|  call_user_func_array_through_class |                      call_user_func |      0.0002s |     0.00019s |                 3.76% |
|  call_user_func_array_through_class |                         call_direct |      0.0002s |     0.00019s |                 5.35% |
|  call_user_func_array_through_class |           call_direct_through_class |      0.0002s |      0.0002s |                 0.81% |
|  call_user_func_array_through_class |                call_user_func_array |      0.0002s |     0.00019s |                 1.76% |
|  call_user_func_array_through_class |        call_user_func_through_class |      0.0002s |      0.0002s |                -0.61% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                      call_user_func |  call_user_func_array_through_class |     0.00019s |      0.0002s |                 -3.9% |
|                      call_user_func |                         call_direct |     0.00019s |     0.00019s |                 1.66% |
|                      call_user_func |           call_direct_through_class |     0.00019s |      0.0002s |                -3.06% |
|                      call_user_func |                call_user_func_array |     0.00019s |     0.00019s |                -2.07% |
|                      call_user_func |        call_user_func_through_class |     0.00019s |      0.0002s |                -4.53% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                         call_direct |  call_user_func_array_through_class |     0.00019s |      0.0002s |                -5.66% |
|                         call_direct |                      call_user_func |     0.00019s |     0.00019s |                -1.69% |
|                         call_direct |           call_direct_through_class |     0.00019s |      0.0002s |                -4.79% |
|                         call_direct |                call_user_func_array |     0.00019s |     0.00019s |                -3.79% |
|                         call_direct |        call_user_func_through_class |     0.00019s |      0.0002s |                -6.29% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|           call_direct_through_class |  call_user_func_array_through_class |      0.0002s |      0.0002s |                -0.82% |
|           call_direct_through_class |                      call_user_func |      0.0002s |     0.00019s |                 2.97% |
|           call_direct_through_class |                         call_direct |      0.0002s |     0.00019s |                 4.58% |
|           call_direct_through_class |                call_user_func_array |      0.0002s |     0.00019s |                 0.96% |
|           call_direct_through_class |        call_user_func_through_class |      0.0002s |      0.0002s |                -1.43% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                call_user_func_array |  call_user_func_array_through_class |     0.00019s |      0.0002s |                 -1.8% |
|                call_user_func_array |                      call_user_func |     0.00019s |     0.00019s |                 2.03% |
|                call_user_func_array |                         call_direct |     0.00019s |     0.00019s |                 3.65% |
|                call_user_func_array |           call_direct_through_class |     0.00019s |      0.0002s |                -0.97% |
|                call_user_func_array |        call_user_func_through_class |     0.00019s |      0.0002s |                -2.41% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|        call_user_func_through_class |  call_user_func_array_through_class |      0.0002s |      0.0002s |                  0.6% |
|        call_user_func_through_class |                      call_user_func |      0.0002s |     0.00019s |                 4.34% |
|        call_user_func_through_class |                         call_direct |      0.0002s |     0.00019s |                 5.92% |
|        call_user_func_through_class |           call_direct_through_class |      0.0002s |      0.0002s |                 1.41% |
|        call_user_func_through_class |                call_user_func_array |      0.0002s |     0.00019s |                 2.36% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
```

```shell
$ php -v
PHP 5.4.3 (cli) (built: May 22 2012 15:05:55)

$ php bench.php 10000
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                                   X VS Y                                  |  X_ = AVG(X) |  Y_ = AVG(Y) | (1 - (Y_ / X_)) * 100 |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|  call_user_func_array_through_class |                      call_user_func |     0.00018s |     0.00018s |                  0.6% |
|  call_user_func_array_through_class |                         call_direct |     0.00018s |     0.00018s |                -0.16% |
|  call_user_func_array_through_class |           call_direct_through_class |     0.00018s |     0.00019s |                -1.73% |
|  call_user_func_array_through_class |                call_user_func_array |     0.00018s |     0.00019s |                 -3.3% |
|  call_user_func_array_through_class |        call_user_func_through_class |     0.00018s |     0.00019s |                -2.02% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                      call_user_func |  call_user_func_array_through_class |     0.00018s |     0.00018s |                 -0.6% |
|                      call_user_func |                         call_direct |     0.00018s |     0.00018s |                -0.76% |
|                      call_user_func |           call_direct_through_class |     0.00018s |     0.00019s |                -2.34% |
|                      call_user_func |                call_user_func_array |     0.00018s |     0.00019s |                -3.92% |
|                      call_user_func |        call_user_func_through_class |     0.00018s |     0.00019s |                -2.63% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                         call_direct |  call_user_func_array_through_class |     0.00018s |     0.00018s |                 0.16% |
|                         call_direct |                      call_user_func |     0.00018s |     0.00018s |                 0.75% |
|                         call_direct |           call_direct_through_class |     0.00018s |     0.00019s |                -1.57% |
|                         call_direct |                call_user_func_array |     0.00018s |     0.00019s |                -3.14% |
|                         call_direct |        call_user_func_through_class |     0.00018s |     0.00019s |                -1.86% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|           call_direct_through_class |  call_user_func_array_through_class |     0.00019s |     0.00018s |                  1.7% |
|           call_direct_through_class |                      call_user_func |     0.00019s |     0.00018s |                 2.29% |
|           call_direct_through_class |                         call_direct |     0.00019s |     0.00018s |                 1.54% |
|           call_direct_through_class |                call_user_func_array |     0.00019s |     0.00019s |                -1.55% |
|           call_direct_through_class |        call_user_func_through_class |     0.00019s |     0.00019s |                -0.29% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|                call_user_func_array |  call_user_func_array_through_class |     0.00019s |     0.00018s |                 3.19% |
|                call_user_func_array |                      call_user_func |     0.00019s |     0.00018s |                 3.78% |
|                call_user_func_array |                         call_direct |     0.00019s |     0.00018s |                 3.04% |
|                call_user_func_array |           call_direct_through_class |     0.00019s |     0.00019s |                 1.52% |
|                call_user_func_array |        call_user_func_through_class |     0.00019s |     0.00019s |                 1.24% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
|        call_user_func_through_class |  call_user_func_array_through_class |     0.00019s |     0.00018s |                 1.98% |
|        call_user_func_through_class |                      call_user_func |     0.00019s |     0.00018s |                 2.57% |
|        call_user_func_through_class |                         call_direct |     0.00019s |     0.00018s |                 1.83% |
|        call_user_func_through_class |           call_direct_through_class |     0.00019s |     0.00019s |                 0.29% |
|        call_user_func_through_class |                call_user_func_array |     0.00019s |     0.00019s |                -1.26% |
+-------------------------------------+-------------------------------------+--------------+--------------+-----------------------+
```