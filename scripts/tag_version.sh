#!/bin/bash

OLD_VERSION=$1
VERSION=`cat ./src/VERSION`
echo ${VERSION}
sed -i '' 's/## Unreleased/## Unreleased\
\
## '"${VERSION}/" ./CHANGELOG.md

git add src/VERSION && \
git add CHANGELOG.md && \
git commit -m "🔖 Update CHANGELOG for release" && \
git push origin main

git tag -a -m "Bump version: ${OLD_VERSION} → ${VERSION}" "release/${VERSION}" && \
git push origin --tags && \
git push origin main
