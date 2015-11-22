-- add table prefix if you have one
DELETE FROM core_config_data WHERE path like 'ffuenf_updatescripts/%';
DELETE FROM core_resource WHERE code = 'ffuenf_updatescripts_setup';
DELETE FROM core_config_data WHERE path = 'advanced/modules_disable_output/Ffuenf_UpdateScripts';