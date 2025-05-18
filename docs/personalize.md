# AWS Personalize Light‐Test Pipeline

This document captures the exact AWS CLI steps used to stand up a low-cost, batch-oriented recommendation pipeline.

## Prerequisites

- IAM user `filmiere-personalize-cli` with:
  - `AmazonS3FullAccess`
  - `AmazonPersonalizeFullAccess`
- S3 bucket: `s3://filmiere-dev`
- Local CSVs:
  - `user_profiles.csv` (with `AGE`,`GENDER` appended)
  - `interactions.csv` (with `TIMESTAMP` appended)

## Steps

1. **Upload data**  
   ```bash
   aws s3 mb s3://filmiere-dev --region ap-south-1  
   aws s3 cp user_profiles_with_demo.csv s3://filmiere-dev/user_profiles.csv  
   aws s3 cp interactions_with_ts.csv  s3://filmiere-dev/interactions.csv  

