ALTER table `users` add `collaborateur_id` bigint unsigned null after `hub_id`
ALTER table `users` add constraint `users_collaborateur_id_foreign` foreign key (`collaborateur_id`) references `collaborateurs` (`id`)

