```shell
$ php -v
PHP 5.3.15-pmsipilot009 with Suhosin-Patch (cli) (built: Jul 27 2012 11:56:28)

$ php bench.php 1000
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                                          VS                                         |        X |        Y | (1 - (Y / X)) * 100 |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|   call_user_func_array_through_class.php |                       call_user_func.php | 0.20260s | 0.19854s |               2.01% |
|   call_user_func_array_through_class.php |                          call_direct.php | 0.20260s | 0.20095s |               0.82% |
|   call_user_func_array_through_class.php |            call_direct_through_class.php | 0.20260s | 0.19847s |               2.04% |
|   call_user_func_array_through_class.php |                 call_user_func_array.php | 0.20260s | 0.20840s |              -2.86% |
|   call_user_func_array_through_class.php |         call_user_func_through_class.php | 0.20260s | 0.20078s |                0.9% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                       call_user_func.php |   call_user_func_array_through_class.php | 0.19854s | 0.20260s |              -2.05% |
|                       call_user_func.php |                          call_direct.php | 0.19854s | 0.20095s |              -1.22% |
|                       call_user_func.php |            call_direct_through_class.php | 0.19854s | 0.19847s |               0.03% |
|                       call_user_func.php |                 call_user_func_array.php | 0.19854s | 0.20840s |              -4.97% |
|                       call_user_func.php |         call_user_func_through_class.php | 0.19854s | 0.20078s |              -1.13% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                          call_direct.php |   call_user_func_array_through_class.php | 0.20095s | 0.20260s |              -0.82% |
|                          call_direct.php |                       call_user_func.php | 0.20095s | 0.19854s |                1.2% |
|                          call_direct.php |            call_direct_through_class.php | 0.20095s | 0.19847s |               1.24% |
|                          call_direct.php |                 call_user_func_array.php | 0.20095s | 0.20840s |              -3.71% |
|                          call_direct.php |         call_user_func_through_class.php | 0.20095s | 0.20078s |               0.09% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|            call_direct_through_class.php |   call_user_func_array_through_class.php | 0.19847s | 0.20260s |              -2.08% |
|            call_direct_through_class.php |                       call_user_func.php | 0.19847s | 0.19854s |              -0.03% |
|            call_direct_through_class.php |                          call_direct.php | 0.19847s | 0.20095s |              -1.25% |
|            call_direct_through_class.php |                 call_user_func_array.php | 0.19847s | 0.20840s |                 -5% |
|            call_direct_through_class.php |         call_user_func_through_class.php | 0.19847s | 0.20078s |              -1.16% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                 call_user_func_array.php |   call_user_func_array_through_class.php | 0.20840s | 0.20260s |               2.78% |
|                 call_user_func_array.php |                       call_user_func.php | 0.20840s | 0.19854s |               4.73% |
|                 call_user_func_array.php |                          call_direct.php | 0.20840s | 0.20095s |               3.57% |
|                 call_user_func_array.php |            call_direct_through_class.php | 0.20840s | 0.19847s |               4.77% |
|                 call_user_func_array.php |         call_user_func_through_class.php | 0.20840s | 0.20078s |               3.66% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|         call_user_func_through_class.php |   call_user_func_array_through_class.php | 0.20078s | 0.20260s |              -0.91% |
|         call_user_func_through_class.php |                       call_user_func.php | 0.20078s | 0.19854s |               1.12% |
|         call_user_func_through_class.php |                          call_direct.php | 0.20078s | 0.20095s |              -0.09% |
|         call_user_func_through_class.php |            call_direct_through_class.php | 0.20078s | 0.19847s |               1.15% |
|         call_user_func_through_class.php |                 call_user_func_array.php | 0.20078s | 0.20840s |               -3.8% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
```

```shell
$ php -v
PHP 5.4.3 (cli) (built: May 22 2012 15:05:55)

$ php bench.php 1000
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                                          VS                                         |        X |        Y | (1 - (Y / X)) * 100 |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|   call_user_func_array_through_class.php |                       call_user_func.php | 0.18868s | 0.18712s |               0.82% |
|   call_user_func_array_through_class.php |                          call_direct.php | 0.18868s | 0.18268s |               3.18% |
|   call_user_func_array_through_class.php |            call_direct_through_class.php | 0.18868s | 0.18623s |                1.3% |
|   call_user_func_array_through_class.php |                 call_user_func_array.php | 0.18868s | 0.19267s |              -2.12% |
|   call_user_func_array_through_class.php |         call_user_func_through_class.php | 0.18868s | 0.18773s |                0.5% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                       call_user_func.php |   call_user_func_array_through_class.php | 0.18712s | 0.18868s |              -0.83% |
|                       call_user_func.php |                          call_direct.php | 0.18712s | 0.18268s |               2.38% |
|                       call_user_func.php |            call_direct_through_class.php | 0.18712s | 0.18623s |               0.48% |
|                       call_user_func.php |                 call_user_func_array.php | 0.18712s | 0.19267s |              -2.96% |
|                       call_user_func.php |         call_user_func_through_class.php | 0.18712s | 0.18773s |              -0.32% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                          call_direct.php |   call_user_func_array_through_class.php | 0.18268s | 0.18868s |              -3.28% |
|                          call_direct.php |                       call_user_func.php | 0.18268s | 0.18712s |              -2.43% |
|                          call_direct.php |            call_direct_through_class.php | 0.18268s | 0.18623s |              -1.94% |
|                          call_direct.php |                 call_user_func_array.php | 0.18268s | 0.19267s |              -5.47% |
|                          call_direct.php |         call_user_func_through_class.php | 0.18268s | 0.18773s |              -2.76% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|            call_direct_through_class.php |   call_user_func_array_through_class.php | 0.18623s | 0.18868s |              -1.31% |
|            call_direct_through_class.php |                       call_user_func.php | 0.18623s | 0.18712s |              -0.48% |
|            call_direct_through_class.php |                          call_direct.php | 0.18623s | 0.18268s |               1.91% |
|            call_direct_through_class.php |                 call_user_func_array.php | 0.18623s | 0.19267s |              -3.46% |
|            call_direct_through_class.php |         call_user_func_through_class.php | 0.18623s | 0.18773s |               -0.8% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|                 call_user_func_array.php |   call_user_func_array_through_class.php | 0.19267s | 0.18868s |               2.07% |
|                 call_user_func_array.php |                       call_user_func.php | 0.19267s | 0.18712s |               2.88% |
|                 call_user_func_array.php |                          call_direct.php | 0.19267s | 0.18268s |               5.19% |
|                 call_user_func_array.php |            call_direct_through_class.php | 0.19267s | 0.18623s |               3.34% |
|                 call_user_func_array.php |         call_user_func_through_class.php | 0.19267s | 0.18773s |               2.57% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
|         call_user_func_through_class.php |   call_user_func_array_through_class.php | 0.18773s | 0.18868s |              -0.51% |
|         call_user_func_through_class.php |                       call_user_func.php | 0.18773s | 0.18712s |               0.32% |
|         call_user_func_through_class.php |                          call_direct.php | 0.18773s | 0.18268s |               2.69% |
|         call_user_func_through_class.php |            call_direct_through_class.php | 0.18773s | 0.18623s |                0.8% |
|         call_user_func_through_class.php |                 call_user_func_array.php | 0.18773s | 0.19267s |              -2.63% |
+------------------------------------------+------------------------------------------+----------+----------+---------------------+
```