AddFavoriUsersTable: alter table `users` add `collaborateur_id` bigint unsigned null
AddFavoriUsersTable: alter table `users` add constraint `users_collaborateur_id_foreign` foreign key (`collaborateur_id`) references `collaborateurs` (`id`)
